<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\ChiefInvigilator;
use App\Models\District;
use App\Models\ExamCandidatesProjection;
use App\Models\ExamVenueConsent;
use App\Models\VenueAssignedCI;
use File;
use Illuminate\Http\Request;
use App\Models\Currentexam;
use App\Services\ExamAuditService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccommodationNotification;
use App\Models\ExamConfirmedHalls;
use App\Models\Venues;
class IDCandidatesController extends Controller
{
    protected $auditService;

    public function __construct(ExamAuditService $auditService)
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
        $this->auditService = $auditService;
    }

    public function updatePercentage(Request $request)
    {
        // Validate the request
        $request->validate([
            'exam_id' => 'required|integer',
            'increment_percentage' => 'required|integer|min:1|max:100',
        ]);

        $examId = $request->input('exam_id');
        $percentage = $request->input('increment_percentage');

        // Retrieve the exam and its related candidates
        $exam = Currentexam::where('exam_main_no', $examId)->first();
        if (!$exam) {
            return redirect()->back()->with('error', 'Exam not found.');
        }

        // Update the candidate count for all rows related to the exam
        DB::table('exam_candidates_projection')
            ->where('exam_id', $examId)
            ->update([
                'accommodation_required' => DB::raw("expected_candidates + (expected_candidates * $percentage / 100)"),
                'increment_percentage' => $percentage,
            ]);

        // Log the update action
        $this->logUpdateAction($exam, $examId, $percentage);

        return redirect()->back()->with('success', 'Candidate counts updated successfully.');
    }

    /**
     * Log the update action with metadata.
     */
    private function logUpdateAction($exam, $examId, $percentage)
    {
        $currentUser = current_user();
        $userName = $currentUser ? $currentUser->display_name : 'Unknown';

        $metadata = [
            'user_name' => $userName,
            'increment_percentage' => $percentage,
        ];

        // Check if a log already exists for this exam and task type
        $existingLog = $this->auditService->findLog([
            'exam_id' => $examId,
            'task_type' => 'id_candidates_update_percentage',
        ]);

        if ($existingLog) {
            // Update the existing log
            $this->auditService->updateLog(
                logId: $existingLog->id,
                metadata: $metadata,
                afterState: $exam->toArray(),
                description: 'Updated candidate counts by percentage'
            );
        } else {
            // Create a new log entry
            $this->auditService->log(
                examId: $examId,
                actionType: 'updated',
                taskType: 'id_candidates_update_percentage',
                afterState: $exam->toArray(),
                description: 'Updated candidate counts by percentage',
                metadata: $metadata
            );
        }
    }

    public function downloadUpdatedCountCsv($examId)
    {
        // Retrieve the updated candidate counts for the given exam ID
        $candidates = DB::table('exam_candidates_projection')
            ->where('exam_id', $examId)
            ->get(['center_code', 'exam_date', 'session', 'expected_candidates', 'accommodation_required', 'increment_percentage']);

        if ($candidates->isEmpty()) {
            return redirect()->back()->with('error', 'No data found for the given exam ID.');
        }

        // Create a CSV file
        $filename = "updated_{$candidates[0]->increment_percentage}_counts_exam_{$examId}.csv";
        $handle = fopen($filename, 'w');
        fputcsv($handle, ['Center Code', 'Date', 'Session', 'Count', 'Accommodation Required']);

        foreach ($candidates as $candidate) {
            fputcsv($handle, [
                "\t" . $candidate->center_code,
                $candidate->exam_date,
                $candidate->session,
                $candidate->expected_candidates,
                $candidate->accommodation_required,
            ]);
        }

        fclose($handle);

        // Return the CSV file as a download response
        return response()->download($filename)->deleteFileAfterSend(true);
    }
    public function showDistrictIntimationForm($examId)
    {
        // Retrieve and group candidates by district code, calculating totals in the query itself
        // First, build a subquery that gets the max candidate count for each center in each district.
        $subQuery = DB::table('exam_candidates_projection')
            ->select(
                'district_code',
                'center_code',
                DB::raw('MAX(accommodation_required) as max_accommodation_required'),
                DB::raw('MAX(expected_candidates) as expected_candidates')
            )
            ->where('exam_id', $examId)
            ->groupBy('district_code', 'center_code');

        // Next, use that subquery as the source to aggregate by district.
        $districts = DB::table(DB::raw("({$subQuery->toSql()}) as sub"))
            ->mergeBindings($subQuery) // Important to merge bindings from the subquery
            ->select(
                'district_code',
                DB::raw('SUM(max_accommodation_required) as total_accommodation_required'),
                DB::raw('SUM(expected_candidates) as expected_candidates'),
                DB::raw('COUNT(center_code) as center_count')
            )
            ->groupBy('district_code')
            ->get();

        if ($districts->isEmpty()) {
            return redirect()->back()->with('error', 'No data found for the given exam ID.');
        }

        // Retrieve total districts and count of districts present in the grouped data
        $totalDistricts = DB::table('district')->count();
        $groupedDistrictCount = $districts->count();

        // Map district names from the district table
        $districtNames = DB::table('district')
            ->whereIn('district_code', $districts->pluck('district_code'))
            ->pluck('district_name', 'district_code');

        // Format the result to include district names
        $districts = $districts->map(function ($district) use ($districtNames) {
            return [
                'district_code' => $district->district_code,
                'district_name' => $districtNames[$district->district_code] ?? 'Unknown',
                'total_accommodation_required' => $district->total_accommodation_required > 0 ? $district->total_accommodation_required : $district->expected_candidates,
                'center_count' => $district->center_count,
            ];
        });

        // Check if a consolidated log already exists
        $existingLog = $this->auditService->findLog([
            'exam_id' => $examId,
            'task_type' => 'id_candidates_update_percentage',
        ]);

        // Extract email logs from the existing log's metadata
        $emailLogs = [];
        $letterDetails = []; // Initialize letter details

        if ($existingLog && isset($existingLog->metadata)) {
            $metadata = is_string($existingLog->metadata) ? json_decode($existingLog->metadata, true) : $existingLog->metadata;
            if (isset($metadata['email_logs'])) {
                $emailLogs = collect($metadata['email_logs'])->mapWithKeys(function ($log) {
                    return [$log['district_code'] => $log['sent_at']];
                });
            }
            // Extract letter details if they exist
            if (isset($metadata['letter_details'])) {
                $letterDetails = $metadata['letter_details'];
            }
        }

        // Merge email sent times with districts
        $districts = $districts->map(function ($district) use ($emailLogs) {
            $district['sent_at'] = $emailLogs[$district['district_code']] ?? null;
            return $district;
        });
        // Pass data to the view
        return view('my_exam.IDCandidates.district-intimation', compact(
            'examId',
            'districts',
            'totalDistricts',
            'groupedDistrictCount',
            'existingLog',
            'letterDetails'
        ));
    }

    public function sendAccommodationEmail(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|string',
            'district_codes' => 'required|array|min:1',
            // 'letter_no' => 'required|string',
            // 'letter_date' => 'required|date',
            // 'exam_controller' => 'required|string',
        ]);

        $examId = $request->input('exam_id');
        $districtCodes = $request->input('district_codes');
        // $letterNo = $request->input('letter_no');
        // $letterDate = $request->input('letter_date');
        // $examController = $request->input('exam_controller');

        // Retrieve the exam and its related candidates
        $exam = Currentexam::where('exam_main_no', $examId)->first();
        if (!$exam) {
            return response()->json(['error' => 'Exam not found.'], 404);
        }

        $emailLogs = []; // Consolidate logs for all districts

        foreach ($districtCodes as $districtCode) {
            // Retrieve the centers in the specified district
            $district = District::where('district_code', $districtCode)->first();
            if (!$district) {
                continue; // Skip if no centers found for the specified district
            }

            // Calculate the required accommodations
            $totalCandidates = DB::table('exam_candidates_projection')
                ->where('exam_id', $examId)
                ->where('district_code', $districtCode)
                ->sum('accommodation_required');
            // Send the email notification
            Mail::to($district->district_email)->send(
                new AccommodationNotification(
                    $exam,
                    $district,
                    $totalCandidates,
                    // $letterNo,
                    // $letterDate,
                    // $examController
                )
            );

            // Add district-specific log to the consolidated array
            $emailLogs[] = [
                'district_code' => $districtCode,
                'district_email' => $district->district_email,
                'total_candidates' => $totalCandidates,
                'sent_at' => now()->toDateTimeString(),
            ];
        }

        // If no emails were sent, return an error response
        if (empty($emailLogs)) {
            return response()->json(['error' => 'No emails were sent.'], 400);
        }


        // Log the email operation
        $existingLog = $this->auditService->findLog([
            'exam_id' => $examId,
            'task_type' => 'id_candidates_update_percentage',
        ]);

        if ($existingLog) {
            // Decode existing metadata and merge new logs
            $existingMetadata = $existingLog->metadata;
            // Update letter details in metadata
            // $existingMetadata['letter_details'] = [
            //     'letter_no' => $letterNo,
            //     'letter_date' => $letterDate,
            //     'exam_controller' => $examController,
            // ];
            if (isset($existingMetadata['email_logs']) && is_array($existingMetadata['email_logs'])) {
                // Merge new email logs with existing ones based on district_code
                foreach ($emailLogs as $newLog) {
                    $index = array_search($newLog['district_code'], array_column($existingMetadata['email_logs'], 'district_code'));

                    if ($index !== false) {
                        // Replace existing log with the new one
                        $existingMetadata['email_logs'][$index] = $newLog;
                    } else {
                        // Add new entry if not already present
                        $existingMetadata['email_logs'][] = $newLog;
                    }
                }
            } else {
                // If no existing logs, set with the new logs
                $existingMetadata['email_logs'] = $emailLogs;
            }

            // Update existing log with merged metadata
            $this->auditService->updateLog(
                logId: $existingLog->id,
                metadata: $existingMetadata,
                afterState: $exam->toArray(),
                description: 'Updated accommodation email notifications.'
            );
        } else {
            // Prepare metadata for new audit log
            $metadata = [
                'exam_id' => $examId,
                // 'letter_details' => [
                //     'letter_no' => $letterNo,
                //     'letter_date' => $letterDate,
                //     'exam_controller' => $examController,
                // ],
                'email_logs' => $emailLogs,
            ];

            $this->auditService->log(
                examId: $examId,
                actionType: 'sent',
                taskType: 'id_candidates_update_percentage',
                afterState: $exam->toArray(),
                description: 'Sent accommodation email notifications.',
                metadata: $metadata
            );
        }

        // Return success response with logs
        return response()->json([
            'success' => true,
            'message' => 'Accommodation emails sent successfully.',
            'logs' => $emailLogs,
        ], 200);
    }
    public function showVenueConfirmationForm(Request $request, $examId)
    {
        // Retrieve the exam
        $exam = Currentexam::where('exam_main_no', $examId)->first();
        if (!$exam) {
            return redirect()->back()->with('error', 'Exam not found.');
        }

        // Retrieve filters from the request
        $selectedDistrict = $request->input('district');
        $selectedCenter = $request->input('center_code');
        $confirmedOnly = $request->input('confirmed_only');
        $selectedDate = $request->input('exam_date');

        // Query confirmed venues and assigned CI
        $confirmedVenuesQuery = ExamVenueConsent::where('exam_id', $examId)
            ->where('consent_status', 'accepted')
            ->with(['venues', 'assignedCIs.chiefInvigilator'])
            ->where('district_code', $selectedDistrict)
            ->where('center_code', $selectedCenter);


        if ($confirmedOnly) {
            $confirmedVenuesQuery->whereHas('assignedCIs', function ($query) {
                $query->where('is_confirmed', true);
            });
        }

        $confirmedVenues = $confirmedVenuesQuery->get();
        $venuesWithCIs = collect();


        foreach ($confirmedVenues as $venue) {
            // Calculate candidate distribution for each venue
            $venueMaxCapacity = $venue->venue_max_capacity;
            $candidatesPerHall = $exam->exam_main_candidates_for_hall;

            $remainingCandidates = $venueMaxCapacity;
            $ciIndex = 0;

            foreach ($venue->assignedCIs as $ci) {
                if (Carbon::parse($ci->exam_date)->format('d-m-Y') == $selectedDate) {
                    // Distribute candidates among CIs
                    $candidatesForCI = min($candidatesPerHall, $remainingCandidates);
                    $remainingCandidates -= $candidatesForCI;

                    $venuesWithCIs->push([
                        'venue' => $venue,
                        'ci' => $ci,
                        'candidates_count' => $candidatesForCI, // Add candidate count for this CI
                    ]);

                    // Stop assigning candidates if no more candidates are left
                    if ($remainingCandidates <= 0) {
                        break;
                    }
                }
            }
        }
        // Order venues by latest update
        $venuesWithCIs = $venuesWithCIs->sortBy('ci.order_by_id')->values();

        // Retrieve districts
        $districts = DB::table('exam_candidates_projection as ecp')
            ->join('district as d', 'ecp.district_code', '=', 'd.district_code')
            ->where('ecp.exam_id', $examId)
            ->select('ecp.district_code', 'd.district_name')
            ->distinct()
            ->get();

        // Retrieve centers
        $centers = DB::table('exam_candidates_projection as ecp')
            ->join('centers as c', 'ecp.center_code', '=', 'c.center_code')
            ->where('ecp.exam_id', $examId)
            ->select('ecp.center_code', 'c.center_name', 'ecp.district_code')
            ->distinct()
            ->get();
        //get all dates for the exam
        $examDates = $exam->examsession->groupBy(function ($item) {
            return Carbon::parse($item->exam_sess_date)->format('d-m-Y');
        })->keys();

        $accommodation_required = \DB::table('exam_candidates_projection')
            ->select(
                \DB::raw('MAX(accommodation_required) as total_accommodation')
            )
            ->where('exam_id', $examId)
            ->where('district_code', $selectedDistrict)
            ->where('center_code', $selectedCenter)
            ->where('exam_date', $selectedDate)
            ->groupBy('center_code')
            ->value('total_accommodation');

        $confirmedVenuesCapacity = ExamVenueConsent::where('exam_id', $examId)
            ->where('district_code', $selectedDistrict)
            ->where('center_code', $selectedCenter)
            ->sum('venue_max_capacity');

        $candidatesCountForEachHall = Currentexam::where('exam_main_no', $examId)
            ->value('exam_main_candidates_for_hall');
        // Pass data to the view
        return view('my_exam.IDCandidates.venue-confirmation', compact('exam', 'confirmedVenues', 'districts', 'examDates', 'centers', 'selectedDistrict', 'selectedCenter', 'confirmedOnly', 'venuesWithCIs', 'accommodation_required', 'confirmedVenuesCapacity', 'candidatesCountForEachHall'));
    }
    public function saveVenueConfirmation(Request $request, $examId)
    {
        // Validate the request
        $validate = $request->validate([
            'selected_venues' => 'required|string',
        ]);

        // Parse the JSON string
        $venuesData = json_decode($validate['selected_venues'], true);
        if (!is_array($venuesData)) {
            return redirect()->back()->with('error', 'Invalid venue data.');
        }
        // Check if "Confirm All Dates" is selected
        $confirmAllDates = $request->has('confirm_all_dates') && $request->input('confirm_all_dates') === 'on';
        $examDates = $confirmAllDates ? $this->getAllExamDates($examId) : [$request->input('exam_date')];
        // Process each venue
        $confirmedInRequest = 0; // Track venues confirmed in this request
        foreach ($examDates as $examDate) {
            foreach ($venuesData as $venueInfo) {
                // Extract venue details
                $venueId = $venueInfo['venue_id'];
                $order = $venueInfo['order'];
                $isChecked = $venueInfo['checked'];
                $ciId = $venueInfo['ci_id'];
                $currentExamDate = $confirmAllDates ? $examDate : ($venueInfo['exam_date'] ?? null);
                // Count confirmed venues in this request
                if ($isChecked) {
                    $confirmedInRequest++;
                }

                // Find the existing venue consent record
                $confirmedVenue = ExamVenueConsent::where('exam_id', $examId)
                    ->where('venue_id', $venueId)
                    ->first();

                if ($confirmedVenue) {

                    // Check if the CI already exists in the new venue_assigned_ci table
                    $venueCI = VenueAssignedCI::where('venue_consent_id', $confirmedVenue->id)
                        ->where('ci_id', $ciId)
                        ->where('exam_date', $currentExamDate)
                        ->first();

                    if ($venueCI) {
                        // Update existing CI assignment
                        $venueCI->update([
                            'order_by_id' => $order,
                            'is_confirmed' => $isChecked,
                        ]);

                        // If unchecked, remove the corresponding hall from ExamConfirmedHalls
                        if (!$isChecked) {
                            ExamConfirmedHalls::where('exam_id', $examId)
                                ->where('venue_code', $confirmedVenue->venues->venue_code)
                                ->where('ci_id', $ciId)
                                ->where('exam_date', $currentExamDate)
                                ->delete();
                        }
                    } else {
                        return redirect()->back()->with('error', 'Venue CI not found.');
                    }
                }
            }
        }

        $exam = Currentexam::where('exam_main_no', $examId)
            ->with([
                'examsession' => function ($query) use ($examDate) {
                    $query->where('exam_sess_date', Carbon::parse($examDate)->format('d-m-Y'));
                }
            ])
            ->first();
        if ($exam->examsession->isEmpty()) {
            return redirect()->back()->with('error', 'Exam not found.');
        }

        $examSessions = $exam->examsession;
        // Generate halls for each session, starting from hall code 001 for each CI

        foreach ($examSessions as $session) {
            $hallCodeCounter = 1;
            // Get confirmed venues based on `venue_assigned_ci` table for the specific exam date
            $confirmedVenues = VenueAssignedCI::with('venueConsent')
                ->where('is_confirmed', true)
                ->whereHas('venueConsent', function ($query) use ($examId, $request) {
                    $query->where('exam_id', $examId)
                        ->where('center_code', $request->center_code);
                })
                ->where('exam_date', $session->exam_sess_date)
                ->orderBy('order_by_id', 'asc')
                ->get();

            if ($confirmedVenues) {
                foreach ($confirmedVenues as $venueAssigned) {
                    $venuecode = $venueAssigned->venueConsent->venues->venue_code ?? null;

                    // Format hall code to be 3 digits (e.g., 001, 002, ...)
                    $hallCode = str_pad($hallCodeCounter, 3, '0', STR_PAD_LEFT);

                    // Find the matching venue data to get the candidate count
                    $venueData = collect($venuesData)->first(function ($item) use ($venueAssigned) {
                        return $item['venue_id'] == $venueAssigned->venueConsent->venue_id &&
                            $item['ci_id'] == $venueAssigned->ci_id &&
                            $item['exam_date'] == $venueAssigned->exam_date;
                    });

                    // Create or update the hall record
                    ExamConfirmedHalls::updateOrCreate(
                        [
                            'exam_id' => $examId,
                            'venue_code' => $venuecode,
                            'district_code' => $venueAssigned->venueConsent->district_code,
                            'center_code' => $venueAssigned->venueConsent->center_code,
                            'ci_id' => $venueAssigned->ci_id,
                            'exam_date' => $venueAssigned->exam_date,
                            'exam_session' => $session->exam_sess_session,
                        ],
                        [
                            'hall_code' => $hallCode,
                            'is_apd_uploaded' => false,
                            'alloted_count' => $venueData ? $venueData['candidates_count'] : null,
                        ]
                    );
                    // Increment hall code for the next CI
                    $hallCodeCounter++;
                }
            }
        }
        // Audit Logging
        $confirmedVenues = VenueAssignedCI::where('is_confirmed', true)
            ->whereHas('venueConsent', function ($query) use ($examId) {
                $query->where('exam_id', $examId);
            })
            ->orderBy('order_by_id', 'asc')
            ->get()
            ->map(function ($venue) {
                return [
                    'venue_id' => $venue->venueConsent->venue_id,
                    'order' => $venue->order_by_id,
                ];
            })
            ->toArray();

        $currentUser = current_user();
        $userName = $currentUser ? $currentUser->display_name : 'Unknown';
        $metadata = ['user_name' => $userName];

        $existingLog = $this->auditService->findLog([
            'exam_id' => $examId,
            'task_type' => 'exam_venue_hall_confirmation',
            'action_type' => 'confirmed',
        ]);

        $totalConfirmed = count($confirmedVenues);
        $description = $confirmedInRequest > 0
            ? "Confirmed {$confirmedInRequest} venues (Total: {$totalConfirmed})"
            : "Updated venue order (Total: {$totalConfirmed})";

        if ($existingLog) {
            $this->auditService->updateLog(
                logId: $existingLog->id,
                metadata: $metadata,
                afterState: [
                    'venues' => $confirmedVenues,
                    'total_confirmed' => $totalConfirmed
                ],
                description: $description
            );
        } else {
            $this->auditService->log(
                examId: $examId,
                actionType: 'confirmed',
                taskType: 'exam_venue_hall_confirmation',
                beforeState: null,
                afterState: [
                    'venues' => $confirmedVenues,
                    'total_confirmed' => $totalConfirmed
                ],
                description: $description,
                metadata: $metadata
            );
        }

        // Redirect back to the confirmation form with success message
        return redirect()->back()->with('success', 'Venues re-order and confirmation updated successfully.');
    }
    private function getAllExamDates($examId)
    {
        $exam = Currentexam::where('exam_main_no', $examId)->with('examsession')->first();
        if (!$exam || $exam->examsession->isEmpty()) {
            return [];
        }
        return $exam->examsession->pluck('exam_sess_date')->map(function ($date) {
            return Carbon::parse($date)->format('Y-m-d');
        })->toArray();
    }
    public function exportToCSV($examId)
    {
        // Retrieve the exam
        $exam = Currentexam::where('exam_main_no', $examId)->first();
        if (!$exam) {
            return redirect()->back()->with('error', 'Exam not found.');
        }

        // Get the first date and first session
        $firstSession = $exam->examsession->first();
        if (!$firstSession) {
            return redirect()->back()->with('error', 'No exam sessions found.');
        }

        $firstDate = Carbon::parse($firstSession->exam_sess_date)->format('d-m-Y');
        $firstSessionNo = $firstSession->exam_sess_session;

        // Retrieve confirmed halls for the first date and session
        $confirmedHalls = ExamConfirmedHalls::where('exam_id', $examId)
            ->where('exam_date', $firstSession->exam_sess_date)
            ->where('exam_session', $firstSessionNo)
            ->orderBy('center_code')
            ->orderBy('hall_code')
            ->get();

        if ($confirmedHalls->isEmpty()) {
            return redirect()->back()->with('error', 'No confirmed halls found for this exam.');
        }

        // Column headers
        $columnHeaders = [
            'HALL CODE',
            'CENTRE CODE',
            'CENTRE NAME',
            'NAME OF THE SCHOOL/COLLEGE',
            'ADDRESS 1',
            'ADDRESS 2',
            'PIN CODE',
            'LAND MARK',
            'PHONE',
            'CAPACITY',
            'GOVT OR PVT',
            'DIST. FROM COLL.(KM)',
            'DIST. FROM RLWY/BUS STN(KM)',
            'COACH/MALP/REM/SENS',
            'CI NAME / CI DESIGNATION',
            'ADDRESS 1',
            'ADDRESS 2',
            'PIN CODE',
            'CI MOBILE',
            'MAIL ID',
            'GPS COORDINATES'
        ];

        // Column widths
        $correctionFactor = 1.1;
        $columnWidths = [
            'A' => 4.89 * $correctionFactor,
            'B' => 5.67 * $correctionFactor,
            'C' => 14.56 * $correctionFactor,
            'D' => 20.11 * $correctionFactor,
            'E' => 16.82 * $correctionFactor,
            'F' => 8.78 * $correctionFactor,
            'G' => 6.22 * $correctionFactor,
            'H' => 11.67 * $correctionFactor,
            'I' => 5.56 * $correctionFactor,
            'J' => 6.22 * $correctionFactor,
            'K' => 5.56 * $correctionFactor,
            'L' => 4.78 * $correctionFactor,
            'M' => 6.67 * $correctionFactor,
            'N' => 5.89 * $correctionFactor,
            'O' => 17.78 * $correctionFactor,
            'P' => 16.98 * $correctionFactor,
            'Q' => 13.22 * $correctionFactor,
            'R' => 5.67 * $correctionFactor,
            'S' => 5.22 * $correctionFactor,
            'T' => 10.78 * $correctionFactor,
            'U' => 11.67 * $correctionFactor
        ];

        // Initialize a temporary directory
        $tempDir = storage_path('app/temp');
        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        // Create Excel file
        $examName = $exam->exam_main_name ?? '';
        $notificationNumber = $exam->exam_main_notification ?? '';
        $mainHeaderText = strtoupper("NOTFN NO.{$notificationNumber}_{$examName} (DOE: {$firstDate})");

        $fileName = "confirmed_halls_exam_{$examId}_{$firstDate}.xlsx";
        $filePath = "$tempDir/$fileName";

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Highest column letter
        $highestColumnIndex = count($columnHeaders);
        $highestColumnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($highestColumnIndex);

        // Main header
        $sheet->setCellValue('A1', $mainHeaderText);
        $sheet->mergeCells("A1:{$highestColumnLetter}1");

        $mainHeaderStyle = [
            'font' => ['name' => 'Calibri', 'bold' => true, 'size' => 18],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ];
        $sheet->getStyle("A1:{$highestColumnLetter}1")->applyFromArray($mainHeaderStyle);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Column headers
        $columnHeaderStyle = [
            'font' => ['name' => 'Verdana', 'bold' => true, 'size' => 10],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ];

        foreach ($columnHeaders as $colIndex => $header) {
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
            $sheet->setCellValue("{$columnLetter}2", $header);
            $sheet->mergeCells("{$columnLetter}2:{$columnLetter}3");
            $sheet->getStyle("{$columnLetter}2:{$columnLetter}3")->applyFromArray($columnHeaderStyle);
        }
        $sheet->getRowDimension(2)->setRowHeight(81);
        $sheet->getRowDimension(3)->setRowHeight(81);

        // Set column widths
        foreach ($columnWidths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
            $sheet->getColumnDimension($column)->setAutoSize(false);
        }

        // Data styles
        $dataCellStyle = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ];

        $verticalTextStyle = [
            'alignment' => [
                'textRotation' => 90,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM,
                'wrapText' => true
            ],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ];

        // Add data
        $rowIndex = 4;
        $totalCapacity = 0;
        foreach ($confirmedHalls as $hall) {
            $ci = ChiefInvigilator::where('ci_venue_id', $hall->venue_code)
                ->where('ci_id', $hall->ci_id)
                ->first() ?? null;

            $capacity = intval($hall->alloted_count ?? '0');
            $totalCapacity += $capacity;

            $ciNameDesignation = $ci ? strtoupper($ci->ci_name) . ", " . "\n" . strtoupper($ci->ci_designation) : '';

            $rowData = [
                strtoupper($hall->hall_code),
                strtoupper($hall->center_code),
                strtoupper($hall->center->center_name),
                strtoupper($hall->venue->venue_name),
                strtoupper($hall->venue->venue_address),
                strtoupper(($hall->center->center_name . ', ' . ($hall->district->district_name ?? ''))),
                strtoupper($hall->venue->venue_pincode ?? 'N/A'),
                strtoupper($hall->venue->venue_landmark ?? 'N/A'),
                strtoupper($hall->venue->venue_phone),
                strtoupper($hall->alloted_count ?? '0'),
                strtoupper($hall->venue->venue_category),
                strtoupper($hall->venue->venue_treasury_office),
                strtoupper($hall->venue->venue_distance_railway),
                ' - ',
                $ciNameDesignation,
                strtoupper($hall->venue->venue_address),
                strtoupper(($hall->center->center_name . ', ' . ($hall->district->district_name ?? ''))),
                strtoupper($hall->venue->venue_pincode ?? 'N/A'),
                strtoupper($ci ? $ci->ci_phone : ''),
                strtoupper($ci ? $ci->ci_email : ''),
                strtoupper(($hall->venue->venue_latitude . ',' . $hall->venue->venue_longitude)),
            ];

            foreach ($rowData as $colIndex => $value) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
                $sheet->setCellValue("{$columnLetter}{$rowIndex}", $value);
                if (in_array($columnLetter, ['I', 'R', 'S'])) {
                    $sheet->getStyle("{$columnLetter}{$rowIndex}")->applyFromArray($verticalTextStyle);
                } else {
                    $sheet->getStyle("{$columnLetter}{$rowIndex}")->applyFromArray($dataCellStyle);
                }
            }
            $sheet->getRowDimension($rowIndex)->setRowHeight(91);
            $rowIndex++;
        }

        // Total row
        $totalRowIndex = $rowIndex;
        $sheet->setCellValue("H{$totalRowIndex}", 'TOTAL');
        $sheet->mergeCells("H{$totalRowIndex}:I{$totalRowIndex}");
        $sheet->setCellValue("J{$totalRowIndex}", $totalCapacity);

        $totalStyle = [
            'font' => ['name' => 'Calibri', 'bold' => true, 'size' => 14],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ];
        $sheet->getStyle("H{$totalRowIndex}:J{$totalRowIndex}")->applyFromArray($totalStyle);
        $sheet->getRowDimension($totalRowIndex)->setRowHeight(25);

        // Save file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filePath);

        // Download file and delete after send
        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}

