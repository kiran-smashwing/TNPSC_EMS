<?php

namespace App\Http\Controllers;

use App\Models\CIMeetingQrcode;
use App\Models\District;
use App\Services\ExamAuditService;
use App\Models\ExamVenueConsent;
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


        $candidatesCountForEachHall = Currentexam::where('exam_main_no', $examId)
            ->value('exam_main_candidates_for_hall');

        $examCenters = \DB::table('exam_candidates_projection')
            ->select('center_code', \DB::raw('SUM(accommodation_required) as total_accommodation'), \DB::raw('COUNT(*) as candidate_count'))
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
        return view('my_exam.District.venue-intimation', compact('examId', 'examCenters', 'user', 'totalCenters', 'totalCentersFromProjection', 'allvenues', 'candidatesCountForEachHall'));
    }
    public function processVenueConsentEmail(Request $request)
    {
        // Validate the request
        $request->validate([
            'center_code' => 'required',
            'exam_id' => 'required',
            'venues' => 'required|array'
        ]);
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        // Get the district code (you might need to derive this from the center code)
        $districtCode = $user->district_code;

        // Process each selected venue
        foreach ($request->venues as $venue) {
            // Create or update exam venue consent record
            ExamVenueConsent::updateOrCreate(
                [
                    'exam_id' => $request->exam_id,
                    'venue_id' => $venue['venue_id'],
                    'center_code' => $request->center_code,
                    'district_code' => $districtCode
                ],
                [
                    'consent_status' => 'requested', // Initial status
                    'email_sent_status' => true,
                    'expected_candidates_count' => $venue['halls_count']  // Assuming 200 candidates per hall
                ]
            );
            // Get the current exam details
            $currentExam = \DB::table('exam_main')->where('exam_main_no', $request->exam_id)->first();

            // Send actual email to venue
            $this->sendVenueConsentEmail($venue['venue_id'], $currentExam);

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

        return response()->json([
            'message' => 'Consent requests sent successfully',
            'venues' => $request->venues
        ]);
    }

    // Optional email sending method
    protected function sendVenueConsentEmail($venueId, $examId)
    {
        // Fetch venue details
        $venue = Venues::findOrFail($venueId);

        // Prepare and send email
        Mail::to("kiran@smashwing.com")->send(new VenueConsentMail($venue, $examId));
    }
    //snappy pdf generation mail function 
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

        //if meeting is already created return the created pdf view 
        $qrCode = DB::table('ci_meeting_qrcode')
            ->where('exam_id', $examId)
            ->where('district_code', $user->district_code)
            ->first();
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
            data: 'https://smashsoft.site/tnpsc-ems/public/login',
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

        if ($qrCode) {
            return redirect()->route('district-candidates.generatePdf', ['qrCodeId' => $qrCode->id]);
        }

        // Combine date and time
        $meetingDateTime = $meetingDate . ' ' . $meetingTime;
        $logoPath = asset('storage/assets/images/qr-code-logo.png'); // replace with your logo path
        // Create the QR code using Builder
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            data: "Exam ID: $examId, Meeting Date & Time: $meetingDateTime, District Code: $user->district_code",
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
                'district_code' => $user->district_code,
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
            ->setOption('displayHeaderFooter', false)
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
    public function generateCIMeetingReport()
    {
        // return view('PDF.Reports.ci-consolidate-report');
     
        $html = view('PDF.Reports.ci-meeting-report')->render();
        $pdf = Browsershot::html($html)
            ->setOption('landscape', true)
            ->setOption('margin', [
                'top' => '4mm',
                'right' => '4mm',
                'bottom' => '8mm',
                'left' => '4mm'
            ])
            ->setOption('displayHeaderFooter', true)
            ->setOption('headerTemplate', '<div></div>')
            ->setOption('footerTemplate', '<div style="font-size:10px;width:100%;text-align:center;">Page <span class="pageNumber"></span> of <span class="totalPages"></span></div>')
            ->setOption('preferCSSPageSize', true)
            ->setOption('printBackground', true)
            ->scale(1)
            ->format('A4')
            ->pdf();
        $filename = 'ci-meeting-attendance-report' . time() . '.pdf';

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

}