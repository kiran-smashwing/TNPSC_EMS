<?php

namespace App\Http\Controllers;

use App\Models\ExamConfirmedHalls;
use App\Models\ExamMaterialsData;
use Illuminate\Http\Request;
use App\Models\Currentexam;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\DB;
use App\Models\Center;
use Illuminate\Support\Facades\Auth;
use App\Models\District;

class DeliveryReportController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
    }

    public function index()
    {
        $districts = District::all(); // Fetch all districts
        // Fetch unique center values from the same table
        $centers = center::all(); // Fetch all venues
        return view('view_report.delivery_report.index', compact('districts', 'centers')); // Path matches the file created
    }
    public function getDropdownData(Request $request)
    {
        $notificationNo = $request->query('notification_no');

        // Check if notification exists
        $examDetails = Currentexam::where('exam_main_notification', $notificationNo)->first();

        if (!$examDetails) {
            return response()->json(['error' => 'Invalid Notification No'], 404);
        }

        // Retrieve the role and guard from session
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;

        // Get the user based on the guard
        $user = $guard ? $guard->user() : null;
        $districtCodeFromSession = $user ? $user->district_code : null; // Assuming district_code is in the user table
        $centerCodeFromSession = $user ? $user->center_code : null; // Assuming center_code is in the user table or retrieved through a relationship

        // Fetch districts - shown for headquarters and district roles
        $districts = [];
        if ($role === 'headquarters' || $role === 'district') {
            $districts = DB::table('district')
                ->select('district_code as id', 'district_name as name')
                ->get();
        }

        // Fetch centers
        $centers = [];
        if ($role === 'headquarters') {
            // Fetch all centers for headquarters
            $centers = DB::table('centers')
                ->select('center_code as id', 'center_name as name')
                ->get();
        } elseif ($role === 'district') {
            // Fetch centers for the specific district
            if ($districtCodeFromSession) {
                $centers = DB::table('centers')
                    ->where('center_district_id', $districtCodeFromSession)
                    ->select('center_code as id', 'center_name as name')
                    ->get();
            }
        }

        // Exam Dates and Sessions - applicable to all roles
        $examDates = DB::table('exam_session')
            ->where('exam_sess_mainid', $examDetails->exam_main_no)
            ->select('exam_sess_date')
            ->distinct()
            ->pluck('exam_sess_date');

        $sessions = DB::table('exam_session')
            ->where('exam_sess_mainid', $examDetails->exam_main_no)
            ->select('exam_sess_session')
            ->distinct()
            ->pluck('exam_sess_session');
        // Return data with logic applied based on roles
        return response()->json([
            'user' => $user,
            'districts' => $districts,
            'centers' => $centers,
            'examDates' => $examDates,
            'sessions' => $sessions,
            'centerCodeFromSession' => $centerCodeFromSession,
        ]);
    }
    public function generateDeliveryReport(Request $request)
    {
        $notificationNo = $request->query('notification_no');
        $examDate = $request->query('exam_date');


        // Fetch the exam details
        $exam_data = Currentexam::with('examservice')
            ->where('exam_main_notification', $notificationNo)
            ->first();

        if (!$exam_data) {
            return back()->with('error', 'Exam data not found.');
        }

        // Fetch the exam ID from the request (or hardcode it for testing)
        $examId = $exam_data->exam_main_no;

        // Fetch exam materials data with related scans
        $examMaterials = ExamMaterialsData::where('exam_id', $examId)
            ->with(['examMaterialsScan', 'center', 'district', 'ci', 'venue'])
            ->get();

        if ($examMaterials->isEmpty()) {
            return back()->with('error', 'No exam materials data found for the given exam ID.');
        }

        // Prepare the session data for the report
        $session_data = [];
        foreach ($examMaterials as $material) {
            $scan = $material->examMaterialsScan;


            // Create a session entry
            $sessionEntry = [
                'district_code' => $material->district_code,
                'district_name' => $material->district->district_name,
                'center_name' => $material->center->center_name,
                'center_code' => $material->center_code,
                'hall_code' => $material->hall_code,
                'venue_name' => $material->venue->venue_name,
                'exam_date' => $material->exam_date,
                'exam_session' => $material->exam_session,
                'qr_code' => $material->qr_code,
                'category' => $material->category,
                'district_scanned_at' => $scan->district_scanned_at ?? false
                    ? \Carbon\Carbon::parse($scan->district_scanned_at)->format('d-m-Y - h:i:s A')
                    : 'N/A',

                'center_scanned_at' => $scan->center_scanned_at ?? false
                    ? \Carbon\Carbon::parse($scan->center_scanned_at)->format('d-m-Y - h:i:s A')
                    : 'N/A',

                'mobile_team_scanned_at' => $scan->mobile_team_scanned_at ?? false
                    ? \Carbon\Carbon::parse($scan->mobile_team_scanned_at)->format('d-m-Y - h:i:s A')
                    : 'N/A',

                'ci_scanned_at' => $scan->ci_scanned_at ?? false
                    ? \Carbon\Carbon::parse($scan->ci_scanned_at)->format('d-m-Y - h:i:s A')
                    : 'N/A',

            ];

            $session_data[] = $sessionEntry;
        }

        // Sort session data by district_code, center_code, and hall_code
        usort($session_data, function ($a, $b) {
            return [$a['district_code'], $a['center_code'], $a['hall_code']] <=> [$b['district_code'], $b['center_code'], $b['hall_code']];
        });

        // Prepare final data for Blade
        $data = [
            'notification_no' => $notificationNo,
            'exam_date' => $examDate,
            'exam_data' => $exam_data,
            'exam_id' => $examId,
            'session_data' => $session_data,
        ];

        // Render the Blade template
        $html = view('view_report.delivery_report.delivery_report_pdf', $data)->render();

        // Generate PDF using Browsershot
        $pdf = Browsershot::html($html)
            ->setOption('landscape', true)
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

        // Define a unique filename for the report
        $filename = 'delivery_report_' . $examId . '_' . time() . '.pdf';

        // Return the PDF as a response
        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');

    }

}
