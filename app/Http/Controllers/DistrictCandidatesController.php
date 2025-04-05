<?php

namespace App\Http\Controllers;

use App\Jobs\SendCIConfirmationEmail;
use App\Models\CIMeetingQrcode;
use App\Models\District;
use App\Models\ExamConfirmedHalls;
use App\Services\ExamAuditService;
use App\Models\ExamVenueConsent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\VenueConsentMail;
use Illuminate\Http\Request;
use App\Models\Currentexam;
use App\Models\Venues;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;


// use Spatie\Browsershot\Browsershot;
class DistrictCandidatesController extends Controller
{
    protected $auditService;

    public function __construct(ExamAuditService $auditService)
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
        $this->auditService = $auditService;
    }
    public function showVenueIntimationForm($examId)
    {
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;
        //get the exam_main_candidates_for_hall data from current exam table with exam id
        $current_exam = Currentexam::where('exam_main_no', $examId)->first();
        $candidatesCountForEachHall = Currentexam::where('exam_main_no', $examId)
            ->value('exam_main_candidates_for_hall');

        $examCenters = \DB::table('exam_candidates_projection')
            ->select(
                'center_code',
                \DB::raw('MAX(accommodation_required) as total_accommodation'),
                \DB::raw('COUNT(*) as session_count')
            )
            ->where('exam_id', $examId)
            ->where('district_code', $user->district_code)
            ->groupBy('center_code')
            ->get();

        $examCenters->each(function ($center) {
            $center->details = \DB::table('centers')
                ->where('center_code', $center->center_code)
                ->first();
        });
        $allvenues = [];
        foreach ($examCenters as $center) {
            $centerVenues = \DB::table('venue')
                ->where('venue_center_id', $center->center_code)
                ->get();
            $allvenues[$center->center_code] = $centerVenues;
        }

        $venueConsents = \DB::table('exam_venue_consent')
            ->where('exam_id', $examId)
            ->where('district_code', $user->district_code)
            ->get()
            ->keyBy('venue_id');

        foreach ($allvenues as $centerCode => $venues) {
            foreach ($venues as $venue) {
                $venue->halls_count = $venueConsents->has($venue->venue_id) ? $venueConsents->get($venue->venue_id)->expected_candidates_count : 0;
                $venue->consent_status = $venueConsents->has($venue->venue_id) ? $venueConsents->get($venue->venue_id)->consent_status : 'not_requested';
            }
        }
        $totalCenters = \DB::table('centers')
            ->where('center_district_id', $user->district_code)
            ->count();
        $totalCentersFromProjection = \DB::table('exam_candidates_projection')
            ->where('exam_id', $examId)
            ->where('district_code', $user->district_code)
            ->distinct('center_code')
            ->count('center_code');
        return view('my_exam.District.venue-intimation', compact('examId', 'current_exam', 'examCenters', 'user', 'totalCenters', 'totalCentersFromProjection', 'allvenues', 'candidatesCountForEachHall'));
    }
    public function reviewVenueIntimationForm(Request $request, $examId)
    {
        // Retrieve the exam
        $exam = Currentexam::where('exam_main_no', $examId)->first();
        if (!$exam) {
            return redirect()->back()->with('error', 'Exam not found.');
        }
        $user = current_user();

        // Retrieve filters from the request
        $selectedDistrict = $user->district_code;
        $selectedCenter = $request->input('center_code');
        $selectedDate = $request->input('exam_date');

        // Query confirmed venues and assigned CI
        $confirmedVenuesQuery = ExamVenueConsent::where('exam_id', $examId)
            ->where('consent_status', 'accepted')
            ->with(['venues', 'assignedCIs.chiefInvigilator'])
            ->where('district_code', $selectedDistrict)
            ->where('center_code', $selectedCenter);

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

        // Retrieve centers
        $centers = DB::table('exam_candidates_projection as ecp')
            ->join('centers as c', 'ecp.center_code', '=', 'c.center_code')
            ->where('ecp.exam_id', $examId)
            ->where('ecp.district_code', $user->district_code)
            ->select('ecp.center_code', 'c.center_name', 'ecp.district_code')
            ->distinct()
            ->get();

        // Get all dates for the exam
        $examDates = $exam->examsession->groupBy(function ($item) {
            return Carbon::parse($item->exam_sess_date)->format('d-m-Y');
        })->keys();

        $accommodation_required = \DB::table('exam_candidates_projection')
            ->select(
                \DB::raw('MAX(accommodation_required) as total_accommodation')
            )
            ->where('exam_id', $examId)
            ->where('district_code', $user->district_code)
            ->where('center_code', $selectedCenter)
            ->where('exam_date', $selectedDate)
            ->groupBy('center_code')
            ->value('total_accommodation');

        $confirmedVenuesCapacity = ExamVenueConsent::where('exam_id', $examId)
            ->where('district_code', $user->district_code)
            ->where('center_code', $selectedCenter)
            ->sum('venue_max_capacity');

        $candidatesCountForEachHall = Currentexam::where('exam_main_no', $examId)
            ->value('exam_main_candidates_for_hall');

        // Pass data to the view
        return view('my_exam.District.review-venue-intimation', compact(
            'exam',
            'confirmedVenues',
            'centers',
            'selectedDistrict',
            'selectedCenter',
            'examDates',
            'venuesWithCIs',
            'accommodation_required',
            'confirmedVenuesCapacity'
        ));
    }
    public function processVenueConsentEmail(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'center_code' => 'required',
                'exam_id' => 'required',
                'venues' => 'required|array',
                'action' => 'required|in:save,send'
            ]);
            $role = session('auth_role');
            $guard = $role ? Auth::guard($role) : null;
            $user = $guard ? $guard->user() : null;
            if (!$user) {
                throw new \Exception('User authentication failed');
            }
            $districtCode = $user->district_code;

            // Process each selected venue
            foreach ($request->venues as $venue) {
                $centercode = Venues::where('venue_id', $venue['venue_id'])->pluck('venue_center_id')->first();
                if (!$centercode) {
                    throw new \Exception("Venue center not found for venue ID: {$venue['venue_id']}");
                }
                $record = ExamVenueConsent::firstOrNew([
                    'exam_id' => $request->exam_id,
                    'venue_id' => $venue['venue_id'],
                    'center_code' => $centercode,
                    'district_code' => $districtCode
                ]);

                // Update common fields
                $record->expected_candidates_count = $venue['halls_count'];

                // Handle email status
                if ($request->action == 'send') {
                    $record->consent_status = 'requested';
                    $record->email_sent_status = true;
                } elseif (!$record->exists) {
                    $record->consent_status = 'saved';
                    $record->email_sent_status = false;
                }

                $record->save();
                // Send email only for send action
                if ($request->action === 'send') {
                    // Get the current exam details
                    $currentExam = \DB::table('exam_main')->where('exam_main_no', $request->exam_id)->first();
                    if (!$currentExam) {
                        throw new \Exception("Exam not found for ID: {$request->exam_id}");
                    }
                    // Send actual email to venue
                    $this->sendVenueConsentEmail($venue, $currentExam);
                }
            }
            // Log the action using the AuditService
            $currentUser = current_user();
            $userName = $currentUser ? $currentUser->display_name : 'Unknown';

            $metadata = [
                'user_name' => $userName,
            ];

            // Check if a log already exists for this exam and task type
            $existingLog = $this->auditService->findLog([
                'exam_id' => $request->exam_id,
                'task_type' => 'exam_venue_consent',
                'user_id' => $user->district_id,
                'action_type' => 'email_sent',
            ]);

            if ($existingLog) {
                // Retrieve existing venues from the previous afterState
                $existingVenues = $existingLog->after_state['venues'] ?? [];

                // Merge existing venues with new venues and remove duplicates
                $mergedVenues = collect(array_merge($existingVenues, $request->venues))
                    ->unique('venue_id')
                    ->values()
                    ->all();
                // Update the existing log
                $this->auditService->updateLog(
                    logId: $existingLog->id,
                    metadata: $metadata,
                    afterState: [
                        'venues' => $mergedVenues,
                        'email_sent_status' => true,
                        'total_venues_count' => count($mergedVenues)
                    ],
                    description: 'Sent consent email to ' . count($request->venues) . ' venues (Total: ' . count($mergedVenues) . ' venues)'
                );
            }
            // Create a new log
            else {
                $this->auditService->log(
                    examId: $request->exam_id,
                    actionType: 'email_sent',
                    taskType: 'exam_venue_consent',
                    beforeState: null,
                    afterState: [
                        'venues' => $request->venues,
                        'email_sent_status' => true,
                        'total_venues_count' => count($request->venues)
                    ],
                    description: 'Sent consent email to ' . count($request->venues) . ' venues',
                    metadata: $metadata
                );
            }
            $message = $request->action == 'send' ? 'Consent requests sent successfully' : 'Saved successfully';

            return response()->json([
                'success' => true,
                'message' => $message,
                'venues' => $request->venues
            ], 200);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Venue consent email processing failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
                'error' => true
            ], 500);
        }
    }
    public function clearSavedVenues(Request $request)
    {
        $request->validate([
            'center_code' => 'required',
            'exam_id' => 'required',
            'venue_ids' => 'required|array'
        ]);

        // Get the current user's district code
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;
        $districtCode = $user->district_code;

        // Clear saved data for selected venues
        ExamVenueConsent::where('exam_id', $request->exam_id)
            ->where('center_code', $request->center_code)
            ->where('district_code', $districtCode)
            ->whereIn('venue_id', $request->venue_ids)
            ->delete();

        // Log the action
        $currentUser = current_user();
        $metadata = ['user_name' => $currentUser->display_name ?? 'Unknown'];

        $this->auditService->log(
            examId: $request->exam_id,
            actionType: 'cleared_saved_venues',
            taskType: 'exam_venue_consent',
            beforeState: null,
            afterState: [
                'cleared_venue_ids' => $request->venue_ids,
                'total_cleared' => count($request->venue_ids)
            ],
            description: 'Cleared saved data for ' . count($request->venue_ids) . ' venues',
            metadata: $metadata
        );

        return response()->json([
            'message' => 'Saved data cleared successfully for selected venues',
            'cleared_venues' => $request->venue_ids
        ]);
    }
    // Optional email sending method
    protected function sendVenueConsentEmail($venue, $exam)
    {
        // dd($venue, $exam);
        // return true;
        // Fetch venue details
        $venueData = Venues::findOrFail($venue['venue_id']);
        // Prepare and send email
        Mail::to($venueData->venue_email)->send(new VenueConsentMail($venue, $venueData, $exam));
    }
    //QRCODE  generation  function 
    public function generateQRCode(Request $request)
    {
        // Get user info
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        // Validate incoming request
        $request->validate([
            'exam_id' => 'required|string',
            'meeting_date' => 'required|date',
            'meeting_time' => 'required|date_format:H:i',
        ]);

        // Retrieve inputs
        $examId = $request->input('exam_id');
        $meetingDate = $request->input('meeting_date');
        $meetingTime = $request->input('meeting_time');
        // Combine date and time
        $meetingDateTime = $meetingDate . ' ' . $meetingTime;

        // Check if a QR code already exists for this exam and district
        $qrCode = DB::table('ci_meeting_qrcode')
            ->where('exam_id', $examId)
            ->where('district_code', $user->district_code ?? '01')
            ->first();
        //     $CIs = ExamConfirmedHalls::selectRaw('
        //     ci_id, 
        //     MIN(id) as id, 
        //     ARRAY_AGG(DISTINCT hall_code) as hall_codes,
        //     ARRAY_AGG(DISTINCT exam_date) as exam_dates,
        // ')
        //         ->where('exam_id', $examId)
        //         ->where('district_code', $user->district_code ?? '01')
        //         ->groupBy('ci_id')
        //         ->get();
        //     dd($CIs);
        // SendCIConfirmationEmail::dispatch($examId, $user->district_code ?? '01');

        if ($qrCode) {
            // Update only the meeting date and time without modifying the QR code
            DB::table('ci_meeting_qrcode')
                ->where('id', $qrCode->id)
                ->update([
                    'meeting_date_time' => $meetingDateTime,
                    'updated_at' => now(),
                ]);

            return redirect()->route('district-candidates.generatePdf', ['qrCodeId' => $qrCode->id])
                ->with('success', 'Meeting date and time updated successfully.');
        }
        //TODO: check if qr code is already created for the url and skip if exisits are already created.
        //generate qr code for this link and send it to view page https://smashsoft.site/tnpsc-ems/public/login
        $logoPath = asset('storage/assets/images/qr-logo.png'); // replace with your logo path

        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            data: 'https://play.google.com/store/apps/details?id=com.tnpsc.ems&pcampaignid=web_share',
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            logoPath: $logoPath,
            logoResizeToWidth: 100,
            logoPunchoutBackground: true,
        );
        // Build the QR code
        $result = $builder->build();

        // Generate a unique file name for the QR code
        $imageName = 'app-qr.png';

        // Define the storage path within the 'public' disk
        $imagePath = 'assets/images/' . $imageName;

        // Store the QR code image using Storage
        Storage::disk('public')->put($imagePath, $result->getString());
        //generate qr code for this link and send it to view page https://smashsoft.site/tnpsc-ems/public/login
        $logoPath = asset('storage/assets/images/qr-logo.png'); // replace with your logo path

        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            data: "{{url('/login')}}",
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            logoPath: $logoPath,
            logoResizeToWidth: 100,
            logoPunchoutBackground: true,
        );
        // Build the QR code
        $result = $builder->build();

        // Generate a unique file name for the QR code
        $imageName = 'website-qr.png';

        // Define the storage path within the 'public' disk
        $imagePath = 'assets/images/' . $imageName;

        // Store the QR code image using Storage
        Storage::disk('public')->put($imagePath, $result->getString());


        // Combine date and time
        $meetingDateTime = $meetingDate . ' ' . $meetingTime;
        $logoPath = asset('storage/assets/images/qr-code-logo.png'); // replace with your logo path
        $district_code = $user->district_code ?? '01';
        // Create the QR code using Builder
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            data: "Exam ID: $examId, Meeting Date & Time: $meetingDateTime, District Code: $district_code",
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            logoPath: $logoPath,
            logoResizeToWidth: 100,
            logoPunchoutBackground: true,
        );

        // Build the QR code
        $result = $builder->build();

        // Generate a unique file name for the QR code
        $imageName = 'exam_' . $examId . '_' . time() . '.png';

        // Define the storage path within the 'public' disk
        $imagePath = 'qrcodes/' . $imageName;

        // Store the QR code image using Storage
        $stored = Storage::disk('public')->put($imagePath, $result->getString());

        // Check if the file was successfully stored
        if ($stored) {
            // Save the QR code details in the database
            $qrCodeId = DB::table('ci_meeting_qrcode')->insertGetId([
                'exam_id' => $examId,
                'district_code' => $user->district_code ?? '01',
                'qrcode' => $imagePath,
                'meeting_date_time' => $meetingDateTime,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Redirect to PDF generation with the QR code ID
            return redirect()->route('district-candidates.generatePdf', ['qrCodeId' => $qrCodeId]);
        } else {
            return redirect()->back()->with('error', 'Failed to save QR Code.');
        }
    }

    public function generatePdf($qrCodeId)
    {

        // Retrieve the QR code details from the database
        $qrCode = CIMeetingQrcode::findOrFail($qrCodeId);
        // If no data found, return error
        if (!$qrCode) {
            return redirect()->back()->with('error', 'QR Code data not found.');
        }
        $examId = $qrCode->exam_id;
        $exam = Currentexam::where('exam_main_no', $examId)->with('examservice')->first();
        $district = District::where('district_code', $qrCode->district_code)->first();

        // return view('PDF.District.ci-meeting-qrcode', [
        //     'qrCodeData' => $qrCode,
        //     'exam' => $exam,
        //     'district' => $district,
        //     'qrCodePath' => Storage::url($qrCode->qrcode)
        // ]);


        $html = view('PDF.District.ci-meeting-qrcode', [
            'qrCodeData' => $qrCode,
            'exam' => $exam,
            'district' => $district,
            'qrCodePath' => Storage::url($qrCode->qrcode)
        ])->render();

        $pdf = Browsershot::html($html)
            ->setOption('landscape', false)
            ->setOption('margin', [
                'top' => '10mm',
                'right' => '10mm',
                'bottom' => '10mm',
                'left' => '10mm'
            ])
            ->setOption('displayHeaderFooter', true)
            ->setOption('headerTemplate', '<div></div>')
            ->setOption('footerTemplate', '
            <div style="font-size:10px;width:100%;text-align:center;">
                Page <span class="pageNumber"></span> of <span class="totalPages"></span>
            </div>
            <div style="position: absolute; bottom: 5mm; right: 10px; font-size: 10px;">
                 IP: ' . $_SERVER['REMOTE_ADDR'] . ' | Timestamp: ' . date('d-m-Y H:i:s') . ' 
            </div>')
            ->setOption('preferCSSPageSize', true)
            ->setOption('printBackground', true)
            ->scale(1)
            ->format('A4')
            ->pdf();

        $filename = 'meeting-qrcode-' . $qrCode->district_code . '-' . $qrCode->exam_id . '-' . time() . '.pdf';

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }


}