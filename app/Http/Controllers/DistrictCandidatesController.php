<?php

namespace App\Http\Controllers;

use App\Services\ExamAuditService;
use App\Models\ExamVenueConsent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\VenueConsentMail;
use Illuminate\Http\Request;
use App\Models\Currentexam;
use App\Models\Venues;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use HeadlessChromium\BrowserFactory;


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

        // Combine date and time
        $meetingDateTime = $meetingDate . ' ' . $meetingTime;

        // Create the QR code using Builder
        $builder = new Builder(
            writer: new PngWriter(),
            data: "Exam ID: $examId, Meeting Date & Time: $meetingDateTime, District Code: $user->district_code",
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300, // QR code size
            margin: 10, // Margin around the QR code
            backgroundColor: new Color(255, 255, 255) // White background
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
            DB::table('ci_meetting_qrcode')->insert([
                'exam_id' => $examId,
                'district_code' => $user->district_code,
                'qrcode' => $imagePath,
                'meeting_date_time' => $meetingDateTime,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Redirect with success message
            return redirect()->back()->with('success', 'QR Code generated and saved successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to save QR Code.');
        }
    }

    // public function generatePdf()
    // {

    //     // return view('pdfs.sample');
    //     // Prepare data for the PDF
    //     $examid = 
    //     $data = [
    //         'title' => 'Sample PDF Report',
    //         'content' => 'This is a test PDF generated using Snappy in Laravel 11.',
    //         'items' => [
    //             ['name' => 'Item 1', 'price' => 10],
    //             ['name' => 'Item 2', 'price' => 20],
    //             ['name' => 'Item 3', 'price' => 30],
    //         ]
    //     ];

    //     // Generate PDF from a Blade view
    //     $pdf = SnappyPdf::loadView('pdfs.sample', $data)
    //         ->setOption('page-size', 'A4');

    //     // Download the PDF
    //     // return $pdf->download('sample_report.pdf');

    //     // Stream PDF in browser
    //     return $pdf->stream('sample_report.pdf');
    // }
    public function generatePdf(Request $request)
    {
        $browserFactory = new BrowserFactory();

        /* starts headless Chrome */

        $browser = (new BrowserFactory())->createBrowser([

            'windowSize' => [1920, 1080]

        ]);

        try {
            // Create a new page and navigate to the rendered HTML view
            $page = $browser->createPage();

            // Render the view dynamically to HTML string
            $htmlContent = view('pdfs.sample')->render();  // Render view to string

            // Set up the page with the HTML content
            $page->setContent($htmlContent);

            // Wait for the content to load (if necessary)
            $page->waitForSelector('body');  // Ensure body is loaded

            // Options for PDF generation
            $options = [
                'landscape' => true,
                'printBackground' => false,
                'marginTop' => 0.0,
                'marginBottom' => 0.0,
                'marginLeft' => 0.0,
                'marginRight' => 0.0,
            ];

            // Define the file name and save the PDF
            $name = public_path("uploads/" . time() . '.pdf');
            $page->pdf($options)->saveToFile($name);

            // Return the PDF as a download response
            return response()->download($name);
        } finally {
            // Close the browser instance
            $browser->close();
        }
    }
}