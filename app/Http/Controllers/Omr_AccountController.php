<?php

namespace App\Http\Controllers;

use App\Models\Currentexam;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\DB;
use App\Models\Center;
use Illuminate\Support\Facades\Auth;
use App\Models\CICandidateLogs;
use App\Models\CIPaperReplacements;
use App\Models\District;
use Illuminate\Http\Request;

class Omr_AccountController extends Controller
{

    public function index()
    {
        $districts = District::all(); // Fetch all districts
        // Fetch unique center values from the same table
        $centers = center::all(); // Fetch all venues
        return view('view_report.omr_report.index', compact('districts', 'centers')); // Path matches the file created
    }
    public function getDropdownData(Request $request)
    {
        $notificationNo = $request->query('notification_no');
        dd($notificationNo);
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
    public function generateReport(Request $request)
    {
        $category = $request->input('category');

        switch ($category) {
            case 'omr_remarks':
                return $this->generateOmrremarksReport($request);
            case 'question_paper':
                return $this->generateQestionpaperReport($request);
            default:
                return response()->json(['error' => 'Invalid category selected'], 400);
        }
    }
  public function generateOmrremarksReport(Request $request)
{
    $notificationNo = $request->query('notification_no');
    $examDate = $request->query('exam_date');
    $session = $request->query('session'); // FN or AN
    $category = $request->query('category');

    // Convert date format if necessary
    $examDate = \Carbon\Carbon::createFromFormat('d-m-Y', $examDate)->format('Y-m-d');

    $exam_data = Currentexam::with('examservice')
        ->where('exam_main_notification', $notificationNo)
        ->first();

    if (!$exam_data) {
        return back()->with('error', 'Exam data not found.');
    }

    $exam_id = $exam_data->exam_main_no;

    $candidate_attendance = CICandidateLogs::where('exam_id', $exam_id)
        ->where('exam_date', $examDate)
        ->with('ci', 'center.district')
        ->get();

    if ($candidate_attendance->isEmpty()) {
        return back()->with('error', 'No candidate attendance found for this exam date.');
    }

    $session_data = [];

    foreach ($candidate_attendance as $attendance) {
        $center = $attendance->center;
        $district = $center->district ?? null;
        $venue = $attendance->ci->venue ?? 'N/A';

        // Decode JSON safely
        $omr_remarks_data = $attendance->omr_remarks;
        if (!is_array($omr_remarks_data)) {
            continue;
        }

        foreach ($omr_remarks_data as $sessionKey => $sessionDetails) {
            if ($session && $sessionKey !== $session) {
                continue;
            }

            if (!isset($sessionDetails['remarks']) || !is_array($sessionDetails['remarks'])) {
                continue;
            }

            $timestamp = $sessionDetails['timestamp'] ?? 'N/A';

            foreach ($sessionDetails['remarks'] as $remark) {
                $session_data[] = [
                    'session' => $sessionKey,
                    'district_name' => $district ? $district->district_name : 'N/A',
                    'center_code' => $center ? $center->center_code : 'N/A',
                    'center_name' => $center ? $center->center_name : 'N/A',
                    'hall_code' => $attendance->hall_code ?? 'N/A',
                    'hall_name' => $venue ? $venue->venue_name : 'N/A',
                    'remarks' => $remark['remark'] ?? 'N/A',
                    'timestamp' => $timestamp,
                    'registration_numbers' => $remark['reg_no'] ?? 'N/A',
                ];
            }
        }
    }

    if (empty($session_data)) {
        return back()->with('error', 'No OMR remarks found for the selected session.');
    }

    $data = [
        'notification_no' => $notificationNo,
        'exam_date' => $examDate,
        'exam_data' => $exam_data,
        'session' => $session,
        'category' => $category,
        'districts' => array_unique(array_column($session_data, 'district_name')),
        'centers' => array_unique(array_column($session_data, 'center_name')),
        'session_data' => $session_data
    ];

    $html = view('view_report.omr_report.omr-account-report', $data)->render();

    $pdf = Browsershot::html($html)
        ->setOption('landscape', true)
        ->setOption('margin', ['top' => '10mm', 'right' => '10mm', 'bottom' => '10mm', 'left' => '10mm'])
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

    $filename = 'omr_account_report_' . time() . '.pdf';

    return response($pdf)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
}






    public function generateQestionpaperReport(Request $request)
    {
        $notificationNo = $request->query('notification_no');
        $examDate = $request->query('exam_date');
        $session = $request->query('session'); // FN or AN
        $category = $request->query('category');

        // Fetch the exam details
        $exam_data = Currentexam::with('examservice')
            ->where('exam_main_notification', $notificationNo)
            ->first();

        if (!$exam_data) {
            return back()->with('error', 'Exam data not found.');
        }

        $exam_id = $exam_data->exam_main_no;

        // Fetch candidate attendance data and filter by session
        $candidate_attendance = CIPaperReplacements::where('exam_id', $exam_id)
            ->where('exam_date', $examDate)
            ->where('exam_session', $session)  // Filter by FN or AN session
            ->with('ci', 'center.district') // Include center and district relationships
            ->get();

        if ($candidate_attendance->isEmpty()) {
            return back()->with('error', 'No candidate attendance found for this exam date and session.');
        }

        // Process and structure the data to pass to the view
        $replacementDetails = $candidate_attendance->map(function ($attendance) {
            return [
                'exam_id' => $attendance->exam_id,
                'center_code' => $attendance->center_code,
                'hall_code' => $attendance->hall_code,
                'exam_date' => $attendance->exam_date,
                'exam_session' => $attendance->exam_session,
                'registration_number'  => $attendance->registration_number ?? '',
                'replacement_type'     => $attendance->replacement_type ?? '',
                'old_paper_number'     => $attendance->old_paper_number ?? '',
                'new_paper_number'     => $attendance->new_paper_number ?? '',
                'replacement_reason'   => $attendance->replacement_reason ?? '',
                'replacement_photo' => !empty($attendance->replacement_photo)
                    ? asset('storage/' . $attendance->replacement_photo)
                    : null, // assuming the path is correct
                'district_name' => $attendance->center->district->district_name ?? 'N/A',
                'center_name' => $attendance->center->center_name ?? 'N/A',
                'venue_name' => $attendance->ci->venue->venue_name ?? 'N/A',
            ];
        });

        // Render the HTML view for the report with replacement details
        $html = view('view_report.omr_report.question-paper-report', [
            'replacementDetails' => $replacementDetails,
            'exam_data' => $exam_data, // Passing exam data
            'session' => $session,
            'notification_no' => $notificationNo, // Passing notification number
            'exam_date' => $examDate // Passing exam date
        ])->render();

        // Generate the PDF
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
        $filename = 'omr_account_reprot' . time() . '.pdf';

        // Return the PDF as a response
        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}
