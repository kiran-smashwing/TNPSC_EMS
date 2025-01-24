<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\ChiefInvigilator;
use App\Models\District;
use App\Models\ExamCandidatesProjection;
use App\Models\ExamVenueConsent;
use Illuminate\Http\Request;
use App\Models\Currentexam;
use App\Services\ExamAuditService;
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
                $candidate->center_code,
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
        $districts = DB::table('exam_candidates_projection')
            ->select(
                'district_code',
                DB::raw('SUM(accommodation_required) as total_accommodation_required'),
                DB::raw('COUNT(DISTINCT center_code) as center_count')
            )
            ->where('exam_id', $examId)
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
                'total_accommodation_required' => $district->total_accommodation_required,
                'center_count' => $district->center_count,
            ];
        });

        // Check if a consolidated log already exists
        $existingLog = $this->auditService->findLog([
            'exam_id' => $examId,
            'task_type' => 'send_accommodation_email',
        ]);

        // Extract email logs from the existing log's metadata
        $emailLogs = [];
        if ($existingLog && isset($existingLog->metadata)) {
            $metadata = is_string($existingLog->metadata) ? json_decode($existingLog->metadata, true) : $existingLog->metadata;
            if (isset($metadata['email_logs'])) {
                $emailLogs = collect($metadata['email_logs'])->mapWithKeys(function ($log) {
                    return [$log['district_code'] => $log['sent_at']];
                });
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
            'existingLog'
        ));
    }

    public function sendAccommodationEmail(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|string',
            'district_codes' => 'required|array|min:1',
        ]);

        $examId = $request->input('exam_id');
        $districtCodes = $request->input('district_codes');

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
            //todo: update the static email to district email  $district->district_email,
            // Send the email notification
            Mail::to('kiran@smashwing.com')->send(new AccommodationNotification($exam, $districtCode, $totalCandidates));

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
            'task_type' => 'send_accommodation_email',
        ]);

        if ($existingLog) {
            // Decode existing metadata and merge new logs
            $existingMetadata = $existingLog->metadata;

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
                'email_logs' => $emailLogs,
            ];

            $this->auditService->log(
                examId: $examId,
                actionType: 'sent',
                taskType: 'send_accommodation_email',
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

        // Query confirmed venues with conditional filtering
        $confirmedVenuesQuery = ExamVenueConsent::where('exam_id', $examId)->where('consent_status', 'accepted')->with('venues')->orderBy('order_by_id', 'asc');

        $confirmedVenuesQuery->where('district_code', $selectedDistrict);

        $confirmedVenuesQuery->where('center_code', $selectedCenter);

        if ($confirmedOnly) {
            $confirmedVenuesQuery->where('is_confirmed', 'true');
        }

        $confirmedVenues = $confirmedVenuesQuery->get();

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

        // Pass data to the view
        return view('my_exam.IDCandidates.venue-confirmation', compact('exam', 'confirmedVenues', 'districts', 'centers', 'selectedDistrict', 'selectedCenter', 'confirmedOnly'));
    }
    public function saveVenueConfirmation(Request $request, $examId)
    {
        // Retrieve the exam
        $exam = Currentexam::where('exam_main_no', $examId)->with(['examsession'])->first();
        if (!$exam) {
            return redirect()->back()->with('error', 'Exam not found.');
        }

        // Validate the request
        $validate = $request->validate([
            'selected_venues' => 'required|string',
        ]);

        // Parse the JSON string
        $venuesData = json_decode($validate['selected_venues'], true);
        if (!is_array($venuesData)) {
            return redirect()->back()->with('error', 'Invalid venue data.');
        }
        // Process each venue
        foreach ($venuesData as $venueInfo) {
            // Extract venue details
            $venueId = $venueInfo['venue_id'];
            $order = $venueInfo['order'];
            $isChecked = $venueInfo['checked'];

            // Find the existing venue consent record
            $confirmedVenue = ExamVenueConsent::where('exam_id', $examId)
                ->where('venue_id', $venueId)
                ->first();

            if ($confirmedVenue) {
                // Always update the order
                $confirmedVenue->order_by_id = $order;

                // Update confirmation status if checked
                $confirmedVenue->is_confirmed = $isChecked;
                //If isChecked is true then genrate the halls for each ci in IDCandidatesController                // Save the changes
                $confirmedVenue->save();
            }
        }
        $examSessions = $exam->examsession;
        // Generate halls for each session, starting from hall code 001 for each CI

        foreach ($examSessions as $session) {
            $hallCodeCounter = 1;
            // get all venues in saved order
            $confirmedVenues = ExamVenueConsent::where('exam_id', $examId)
                ->where('is_confirmed', 'true')
                ->orderBy('order_by_id', 'asc')
                ->get();
            if ($confirmedVenues) {
                foreach ($confirmedVenues as $confirmedVenue) {
                    $venuecode = Venues::where('venue_id', $confirmedVenue->venue_id)->first()->venue_code ?? null;
                    // Ensure chief_invigilator_data is in the correct format
                    $ciData = is_string($confirmedVenue->chief_invigilator_data)
                        ? json_decode($confirmedVenue->chief_invigilator_data, true)
                        : $confirmedVenue->chief_invigilator_data;
                    foreach ($ciData as $ci) {

                        // Get the exam date and session for the current CI
                        $examDate = $ci['exam_date'];
                        $ciId = $ci['ci_id'];
                        if (\Carbon\Carbon::parse($session->exam_sess_date)->format('Y-m-d') != $ci['exam_date']) {
                            continue;
                        }

                        // Format hall code to be 3 digits (e.g., 001, 002, ...)
                        $hallCode = str_pad($hallCodeCounter, 3, '0', STR_PAD_LEFT);

                        // Create or update the hall record
                        ExamConfirmedHalls::updateOrCreate(
                            [
                                'exam_id' => $examId,
                                'venue_code' => $venuecode,
                                'hall_code' => $hallCode,
                                'ci_id' => $ciId,
                                'exam_date' => $examDate,
                                'exam_session' => $session->exam_sess_session,
                            ],
                            [
                                'district_code' => $confirmedVenue->district_code,
                                'center_code' => $confirmedVenue->center_code,
                                'is_apd_uploaded' => false,
                                'alloted_count' => null,
                            ]
                        );
                        // Increment hall code for the next CI
                        $hallCodeCounter++;

                    }

                }
            }
        }

        // Redirect back to the confirmation form with success message
        return redirect()->back()->with('success', 'Venues re-order and confirmation updated successfully.');
    }

    public function exportToCSV($examId)
    {
        // Retrieve the exam
        $exam = Currentexam::where('exam_main_no', $examId)->first();
        if (!$exam) {
            return redirect()->back()->with('error', 'Exam not found.');
        }

        // Retrieve the confirmed halls for the exam
        $confirmedHalls = ExamConfirmedHalls::where('exam_id', $examId)->get();

        // Prepare the data for CSV export
        $csvData = [];
        foreach ($confirmedHalls as $hall) {
            $hall->centername = Center::where('center_code', $hall->center_code)->first()->center_name ?? null;
            $hall->venue = Venues::where('venue_code', $hall->venue_code)->first() ?? null;
            $ci = ChiefInvigilator::where('ci_venue_id', $hall->venue_code)->where('ci_id', $hall->ci_id)->first() ?? null;
            // Format the data for CSV export
            $csvData[] = [
                'Hall Code' => $hall->hall_code,
                'CenterCode & Name' => $hall->center_code . ' - ' . $hall->centername,
                'Hall Name' => $hall->venue->venue_name,
                'Address' => $hall->venue->venue_address,
                'Phone' => $hall->venue->venue_phone,
                'GOVT OR PVT' => $hall->venue->venue_category,
                'DIST. FROM COLL.' => $hall->venue->venue_treasury_office,
                'DIST. FROM RLWY/BUS STN' => $hall->venue->venue_distance_railway,
                'CI NAME & ADDRESS' => $ci->ci_name . ' - ' . $ci->ci_phone,
                'CI PHONE' => $ci->ci_phone,
                'CI EMAIL' => $ci->ci_email,
                'EXAM DATE' => $hall->exam_date,
                'EXAM SESSION' => $hall->exam_session,
                'GPS_COORD' => $hall->venue->venue_latitude . ',' . $hall->venue->venue_longitude,
            ];
        }

        // Generate the CSV string
        $csvString = fopen('php://temp', 'r+');
        fputcsv($csvString, array_keys($csvData[0]));
        foreach ($csvData as $row) {
            $row['Hall Code'] = sprintf('="%s"', $row['Hall Code']);
            fputcsv($csvString, $row);
        }
        rewind($csvString);

        // Return the CSV string as a response
        return response(stream_get_contents($csvString), 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="confirmed_halls_exam_' . $examId . '.csv"');
    }


}
