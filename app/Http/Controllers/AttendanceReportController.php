<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currentexam;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\DB;
use App\Models\Center;
use App\Models\CIcandidateLogs;
use Illuminate\Support\Facades\Auth;
use App\Models\District;

class AttendanceReportController extends Controller
{

    public function index()
    {
        $districts = District::all(); // Fetch all districts
        // Fetch unique center values from the same table
        $centers = center::all(); // Fetch all venues
        return view('view_report.attendance_report.index', compact('districts', 'centers')); // Path matches the file created
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
    public function generatecategorysender(Request $request)
    {
        $category = $request->query('category'); // category can be district, center, or overall

        switch ($category) {
            case 'district':
                return $this->generateAttendanceReportDistrict($request);
                break;

            case 'center':
                return $this->generateAttendanceReport($request);
                break;

            case 'all':
                return $this->generateAttendanceReportOverall($request);
                break;

            default:
                return back()->with('error', 'Invalid category specified.');
        }
    }

    public function generateAttendanceReport(Request $request)
    {
        // Get parameters from the request
        $notificationNo = $request->query('notification_no');
        $examDate = $request->query('exam_date');
        $session = $request->query('session'); // FN or AN
        $category = $request->query('category');
        $district = $request->query('district');
        $center = $request->query('center');  // Center ID for filtering
    
        // Fetch the exam details
        $exam_data = Currentexam::with('examservice')
            ->where('exam_main_notification', $notificationNo)
            ->first();
    
        if (!$exam_data) {
            return back()->with('error', 'Exam data not found.');
        }
    
        $exam_id = $exam_data->exam_main_no;
    
        // Log the parameters for debugging
        \Log::info('Exam ID: ' . $exam_id);
        \Log::info('Exam Date: ' . $examDate);
        \Log::info('District: ' . $district);
        \Log::info('Center: ' . $center);
    
        // Start SQL Query Debugging
        \DB::listen(function ($query) {
            \Log::info('SQL Query: ' . $query->sql);
            \Log::info('Bindings: ' . implode(', ', $query->bindings));
            \Log::info('Time: ' . $query->time . 'ms');
        });
    
        // Simplified candidate attendance query
        $candidate_attendance = CIcandidateLogs::join('centers', 'ci_candidate_logs.center_code', '=', 'centers.center_code')
            ->where('exam_id', $exam_id)
            ->where('exam_date', $examDate)
            ->when($district, function ($query) use ($district) {
                // Filter by district in the centers table
                return $query->where('centers.center_district_id', $district);
            })
            ->when($center, function ($query) use ($center) {
                // Filter by center in the centers table
                return $query->where('centers.center_id', $center);
            })
            ->select('ci_candidate_logs.*', 'centers.center_code', 'centers.center_name')  // Select necessary columns
            ->get();
    
        // Dump the query result for debugging
        dd($candidate_attendance);
    
        // Check if no results were found
        if ($candidate_attendance->isEmpty()) {
            return back()->with('error', 'No candidate attendance found for this exam date, district, or center.');
        }
    
        // Extract and format session data dynamically
        $session_data = [];
        $district_name = null;  // Initialize district_name
    
        foreach ($candidate_attendance as $attendance) {
            $center = $attendance->center;
            $district = $center->district ?? null;
            $venue = $attendance->ci->venue ?? 'N/A'; // Access the venue name from the `ci` relationship
    
            // Decode candidate_attendance JSON if stored as a string
            $attendance_data = is_string($attendance->candidate_attendance)
                ? json_decode($attendance->candidate_attendance, true)
                : $attendance->candidate_attendance;
    
            // If a district is available, assign it only once (first occurrence)
            if ($district_name === null && $district) {
                $district_name = $district->district_name ?? 'N/A';
            }
    
            // Loop through session-wise attendance (AN, FN, etc.)
            foreach ($attendance_data as $sessionKey => $sessionAttendance) {
                // Check if the session matches the requested session (FN or AN)
                if ($session && $sessionKey != $session) {
                    continue; // Skip if session doesn't match the requested session
                }
    
                // Create a session entry
                $sessionEntry = [
                    'session' => $sessionKey,
                    'center_code' => $center ? $center->center_code : 'N/A',
                    'center_name' => $center ? $center->center_name : 'N/A',
                    'hall_code' => $attendance->hall_code ?? 'N/A',
                    'hall_name' => $venue ? $venue->venue_name : 'N/A',
                    'present' => $sessionAttendance['present'] ?? 0,
                    'absent' => $sessionAttendance['absent'] ?? 0,
                    'total_candidates' => $sessionAttendance['alloted_count'] ?? 0,
                    'percentage' => ($sessionAttendance['alloted_count'] > 0)
                        ? number_format(($sessionAttendance['present'] / $sessionAttendance['alloted_count']) * 100, 2) . '%'
                        : '0%',
                    'timestamp' => $sessionAttendance['timestamp'] ?? 'N/A',
                ];
    
                // Add the session entry to the session_data array
                $session_data[] = $sessionEntry;
            }
        }
    
        // Prepare final data for Blade
        $data = [
            'notification_no' => $notificationNo,
            'exam_date' => $examDate,
            'exam_data' => $exam_data,
            'session' => $session,
            'category' => $category,
            'district' => $district_name ?? 'N/A',  // Pass the unique district
            'center' => $center,  // Pass the center
            'session_data' => $session_data
        ];
    
        // Render the Blade template
        $html = view('view_report.attendance_report.attendance_report_pdf', $data)->render();
    
        // Generate PDF using Browsershot
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
    
        // Define a unique filename for the report
        $filename = 'attendance_report_' . $center . '_' . time() . '.pdf';
    
        // Return the PDF as a response
        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
    



    public function generateAttendanceReportDistrict(Request $request)
    {
        $notificationNo = $request->query('notification_no');
        $examDate = $request->query('exam_date');
        $session = $request->query('session'); // FN or AN
        $category = $request->query('category');
        $district = $request->query('district');
        $center = $request->query('center');  // Center ID for filtering

        // Fetch the exam details
        $exam_data = Currentexam::with('examservice')
            ->where('exam_main_notification', $notificationNo)
            ->first();

        if (!$exam_data) {
            return back()->with('error', 'Exam data not found.');
        }

        $exam_id = $exam_data->exam_main_no;

        // Fetch candidate attendance data, filtered by center if centerId is provided
        $candidate_attendance = CIcandidateLogs::where('exam_id', $exam_id)
            ->where('exam_date', $examDate)
            ->when($center, function ($query) use ($center) {
                return $query->where('center_id', $center);  // Filter by center
            })
            ->with('ci')
            ->get();

        if ($candidate_attendance->isEmpty()) {
            return back()->with('error', 'No candidate attendance found for this exam date and center.');
        }

        // Extract and format session data dynamically
        $session_data = [];
        $district_name = null;  // Initialize district_name

        foreach ($candidate_attendance as $attendance) {
            $center = $attendance->center;
            $district = $center->district ?? null;
            $venue = $attendance->ci->venue ?? 'N/A'; // Access the venue name from the `ci` relationship

            // Decode candidate_attendance JSON if stored as a string
            $attendance_data = is_string($attendance->candidate_attendance)
                ? json_decode($attendance->candidate_attendance, true)
                : $attendance->candidate_attendance;

            // If a district is available, assign it only once (first occurrence)
            if ($district_name === null && $district) {
                $district_name = $district->district_name ?? 'N/A';
            }

            // Loop through session-wise attendance (AN, FN, etc.)
            foreach ($attendance_data as $sessionKey => $sessionAttendance) {
                // Check if the session matches the requested session (FN or AN)
                if ($session && $sessionKey != $session) {
                    continue; // Skip if session doesn't match the requested session
                }

                // Create a session entry
                $sessionEntry = [
                    'session' => $sessionKey,
                    'center_code' => $center ? $center->center_code : 'N/A',
                    'center_name' => $center ? $center->center_name : 'N/A',
                    'hall_code' => $attendance->hall_code ?? 'N/A',
                    'hall_name' => $venue ? $venue->venue_name : 'N/A',
                    'present' => $sessionAttendance['present'] ?? 0,
                    'absent' => $sessionAttendance['absent'] ?? 0,
                    'total_candidates' => $sessionAttendance['alloted_count'] ?? 0,
                    'percentage' => ($sessionAttendance['alloted_count'] > 0)
                        ? number_format(($sessionAttendance['present'] / $sessionAttendance['alloted_count']) * 100, 2) . '%'
                        : '0%',
                    'timestamp' => $sessionAttendance['timestamp'] ?? 'N/A',
                ];

                // Add the session entry to the session_data array
                $session_data[] = $sessionEntry;
            }
        }

        // Prepare final data for Blade
        $data = [
            'notification_no' => $notificationNo,
            'exam_date' => $examDate,
            'exam_data' => $exam_data,
            'session' => $session,
            'category' => $category,
            'district' => $district_name ?? 'N/A',  // Ensure only one district is passed
            'session_data' => $session_data
        ];

        // Render the Blade template
        $html = view('view_report.attendance_report.attendance_reprot_district', $data)->render();

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
        $filename = 'attendance_report_district' . $center . '_' . time() . '.pdf';

        // Return the PDF as a response
        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

    public function generateAttendanceReportOverall(Request $request)
    {
        $notificationNo = $request->query('notification_no');
        $examDate = $request->query('exam_date');
        $session = $request->query('session'); // FN or AN
        $category = $request->query('category');
        $districtId = $request->query('district');
        $centerId = $request->query('center');
        // dd($districtId);
        // Fetch the exam details
        $exam_data = Currentexam::with('examservice')
            ->where('exam_main_notification', $notificationNo)
            ->first();

        if (!$exam_data) {
            return back()->with('error', 'Exam data not found.');
        }

        $exam_id = $exam_data->exam_main_no;

        // Fetch candidate attendance data
        $candidate_attendance = CIcandidateLogs::where('exam_id', $exam_id)
            ->where('exam_date', $examDate)
            ->with('ci')
            ->get();
        // $ci = $candidate_attendance->ci;
        // dd($candidate_attendance[0]->ci->venue);
        if ($candidate_attendance->isEmpty()) {
            return back()->with('error', 'No candidate attendance found for this exam date.');
        }

        // Extract and format session data dynamically
        $session_data = [];

        foreach ($candidate_attendance as $attendance) {
            $center = $attendance->center;
            $district = $center->district ?? null;
            $venue = $attendance->ci->venue ?? 'N/A'; // Access the venue name from the `ci` relationship

            // Decode candidate_attendance JSON if stored as a string
            $attendance_data = is_string($attendance->candidate_attendance)
                ? json_decode($attendance->candidate_attendance, true)
                : $attendance->candidate_attendance;

            // Loop through session-wise attendance (AN, FN, etc.)
            foreach ($attendance_data as $sessionKey => $sessionAttendance) {
                // Check if the session matches the requested session (FN or AN)
                if ($session && $sessionKey != $session) {
                    continue; // Skip if session doesn't match the requested session
                }

                // Create a session entry
                $sessionEntry = [
                    'session' => $sessionKey,
                    'district_name' => $district ? $district->district_name : 'N/A',
                    'center_code' => $center ? $center->center_code : 'N/A',
                    'center_name' => $center ? $center->center_name : 'N/A',
                    'hall_code' => $attendance->hall_code ?? 'N/A',
                    'hall_name' => $venue ? $venue->venue_name : 'N/A',
                    'present' => $sessionAttendance['present'] ?? 0,
                    'absent' => $sessionAttendance['absent'] ?? 0,
                    'total_candidates' => $sessionAttendance['alloted_count'] ?? 0,
                    'percentage' => ($sessionAttendance['alloted_count'] > 0)
                        ? number_format(($sessionAttendance['present'] / $sessionAttendance['alloted_count']) * 100, 2) . '%'
                        : '0%',
                    'timestamp' => $sessionAttendance['timestamp'] ?? 'N/A',
                ];

                // Add the session entry to the session_data array
                $session_data[] = $sessionEntry;
            }
        }

        // Prepare final data for Blade
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

        // Render the Blade template
        $html = view('view_report.attendance_report.attendance_report_overall', $data)->render();

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
        $filename = 'attendance_report_overall_' . time() . '.pdf';

        // Return the PDF as a response
        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}
