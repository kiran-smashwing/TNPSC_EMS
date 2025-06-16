<?php

namespace App\Http\Controllers;

use App\Models\ExamConfirmedHalls;
use Illuminate\Http\Request;
use App\Models\Currentexam;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\DB;
use App\Models\Center;
use App\Models\CICandidateLogs;
use Illuminate\Support\Facades\Auth;
use App\Models\District;

class AttendanceReportController extends Controller
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
        $notificationNo = $request->query('notification_no');
        $examDate = $request->query('exam_date');
        $session = $request->query('session'); // FN or AN
        $category = $request->query('category');
        $districtId = $request->query('district');
        $centerId = $request->query('center');  // Center ID for filtering
    
        // Fetch the exam details
        $exam_data = Currentexam::with('examservice')
            ->where('exam_main_notification', $notificationNo)
            ->first();
    
        if (!$exam_data) {
            return back()->with('error', 'Exam data not found.');
        }
    
        $exam_id = $exam_data->exam_main_no;
    
        // Fetch all confirmed halls for the given exam date
        $examconfirmed_halls = ExamConfirmedHalls::with([
            'district',
            'center',
            'venue',
            'chiefInvigilator.venue',
            'ciCandidateLogs'
        ])
            ->when($districtId, function ($query) use ($districtId) {
                return $query->where('district_code', $districtId); // Filter by district
            })
            ->when($centerId, function ($query) use ($centerId) {
                return $query->where('center_code', $centerId); // Filter by center
            })
            ->where('exam_id', $exam_id)
            ->where('exam_date', $examDate)
            ->get();
    
        // Fetch candidate attendance data
        $candidate_attendance_logs = CICandidateLogs::where('exam_id', $exam_id)
            ->where('exam_date', $examDate)
            ->with('ci')
            ->get()
            ->keyBy(function ($log) {
                return $log->ci_id . '-' . $log->hall_code; // Key by CI ID and Hall Code for quick lookup
            });
    
        $session_data = [];
        $district_name = null; // Initialize district_name
        $center_name = null;   // Initialize center_name
    
        foreach ($examconfirmed_halls as $hall) {
            $district = $hall->district;
            $center = $hall->center;
            $venue = $hall->venue ?? $hall->chiefInvigilator->venue ?? null;
    
            // Set district name only once
            if ($district_name === null && $district) {
                $district_name = $district->district_name ?? 'N/A';
            }
    
            // Set center name only once
            if ($center_name === null && $center) {
                $center_name = $center->center_name ?? 'N/A';
            }
    
            foreach (['FN', 'AN'] as $sessionKey) {
                if ($session && $session !== $sessionKey) {
                    continue; // Skip if session doesn't match the requested session
                }
    
                // Default attendance data
                $attendance_data = [
                    'present' => 0,
                    'absent' => 0,
                    'alloted_count' => $hall->alloted_count ?? 0, // Use hall's allotted count if available
                    'timestamp' => 'N/A'
                ];
    
                // Check if attendance log exists for this hall
                $logKey = $hall->ci_id . '-' . $hall->hall_code;
                if (isset($candidate_attendance_logs[$logKey]) && $candidate_attendance_logs[$logKey]->candidate_attendance) {
                    $decoded_attendance = is_string($candidate_attendance_logs[$logKey]->candidate_attendance)
                        ? json_decode($candidate_attendance_logs[$logKey]->candidate_attendance, true)
                        : $candidate_attendance_logs[$logKey]->candidate_attendance;
    
                    $attendance_data = array_merge($attendance_data, $decoded_attendance[$sessionKey] ?? []);
                }
    
                // Create a session entry
                $sessionEntry = [
                    'session' => $sessionKey,
                    'center_code' => $center->center_code ?? 'N/A',
                    'center_name' => $center->center_name ?? 'N/A',
                    'hall_code' => $hall->hall_code ?? 'N/A',
                    'hall_name' => $venue->venue_name ?? 'N/A',
                    'present' => $attendance_data['present'] ?? 0,
                    'absent' => $attendance_data['absent'] ?? 0,
                    'total_candidates' => $attendance_data['alloted_count'] ?? 0,
                    'percentage' => isset($attendance_data['alloted_count']) && $attendance_data['alloted_count'] > 0
                        ? number_format(($attendance_data['present'] / $attendance_data['alloted_count']) * 100, 2) . '%'
                        : '0%',
                    'timestamp' => $attendance_data['timestamp'] ?? 'N/A',
                ];
    
                $session_data[] = $sessionEntry;
            }
        }
    
        // Sort session data by center_code and hall_code
        usort($session_data, function ($a, $b) {
            return [$a['center_code'], $a['hall_code']] <=> [$b['center_code'], $b['hall_code']];
        });
    
        // Prepare final data for Blade
        $data = [
            'notification_no' => $notificationNo,
            'exam_date' => $examDate,
            'exam_data' => $exam_data,
            'session' => $session,
            'category' => $category,
            'center_code' => $center_name ? $center_name : 'N/A',
            'center_name' => $center_name ? $center_name : 'N/A',
            'district' => $district_name ?? 'N/A',
            'session_data' => $session_data
        ];
    
        // Render the Blade template
        $html = view('view_report.attendance_report.attendance_report_pdf', $data)->render();
    
        // Generate PDF using Browsershot
        $pdf = Browsershot::html($html)
            ->setOption('protarit', true)
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
        $filename = 'attendance_report_center_' . ($center_name ?? 'all') . '_' . time() . '.pdf';
    
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
        $districtId = $request->query('district');
        $centerId = $request->query('center');  // Center ID for filtering
    
        // Fetch the exam details
        $exam_data = Currentexam::with('examservice')
            ->where('exam_main_notification', $notificationNo)
            ->first();
    
        if (!$exam_data) {
            return back()->with('error', 'Exam data not found.');
        }
    
        $exam_id = $exam_data->exam_main_no;
    
        // Fetch all confirmed halls for the given exam date
        $examconfirmed_halls = ExamConfirmedHalls::with([
            'district',
            'center',
            'venue',
            'chiefInvigilator.venue',
            'ciCandidateLogs'
        ])
            ->when($districtId, function ($query) use ($districtId) {
                return $query->where('district_code', $districtId); // Filter by district
            })
            ->when($centerId, function ($query) use ($centerId) {
                return $query->where('center_code', $centerId); // Filter by center
            })
            ->where('exam_id', $exam_id)
            ->where('exam_date', $examDate)
            ->get();
    
        // Fetch candidate attendance data
        $candidate_attendance_logs = CICandidateLogs::where('exam_id', $exam_id)
            ->where('exam_date', $examDate)
            ->with('ci')
            ->get()
            ->keyBy(function ($log) {
                return $log->ci_id . '-' . $log->hall_code; // Key by CI ID and Hall Code for quick lookup
            });
    
        $session_data = [];
        $district_name = null; // Initialize district_name
    
        foreach ($examconfirmed_halls as $hall) {
            $district = $hall->district;
            $center = $hall->center;
            $venue = $hall->venue ?? $hall->chiefInvigilator->venue ?? null;
    
            // Set district name only once
            if ($district_name === null && $district) {
                $district_name = $district->district_name ?? 'N/A';
            }
    
            foreach (['FN', 'AN'] as $sessionKey) {
                if ($session && $session !== $sessionKey) {
                    continue; // Skip if session doesn't match the requested session
                }
    
                // Default attendance data
                $attendance_data = [
                    'present' => 0,
                    'absent' => 0,
                    'alloted_count' => $hall->alloted_count ?? 0, // Use hall's allotted count if available
                    'timestamp' => 'N/A'
                ];
    
                // Check if attendance log exists for this hall
                $logKey = $hall->ci_id . '-' . $hall->hall_code;
                if (isset($candidate_attendance_logs[$logKey]) && $candidate_attendance_logs[$logKey]->candidate_attendance) {
                    $decoded_attendance = is_string($candidate_attendance_logs[$logKey]->candidate_attendance)
                        ? json_decode($candidate_attendance_logs[$logKey]->candidate_attendance, true)
                        : $candidate_attendance_logs[$logKey]->candidate_attendance;
    
                    $attendance_data = array_merge($attendance_data, $decoded_attendance[$sessionKey] ?? []);
                }
    
                // Create a session entry
                $sessionEntry = [
                    'session' => $sessionKey,
                    'center_code' => $center->center_code ?? 'N/A',
                    'center_name' => $center->center_name ?? 'N/A',
                    'hall_code' => $hall->hall_code ?? 'N/A',
                    'hall_name' => $venue->venue_name ?? 'N/A',
                    'present' => $attendance_data['present'] ?? 0,
                    'absent' => $attendance_data['absent'] ?? 0,
                    'total_candidates' => $attendance_data['alloted_count'] ?? 0,
                    'percentage' => isset($attendance_data['alloted_count']) && $attendance_data['alloted_count'] > 0
                        ? number_format(($attendance_data['present'] / $attendance_data['alloted_count']) * 100, 2) . '%'
                        : '0%',
                    'timestamp' => $attendance_data['timestamp'] ?? 'N/A',
                ];
    
                $session_data[] = $sessionEntry;
            }
        }
    
        // Sort session data by center_code and hall_code
        usort($session_data, function ($a, $b) {
            return [$a['center_code'], $a['hall_code']] <=> [$b['center_code'], $b['hall_code']];
        });
    
        // Prepare final data for Blade
        $data = [
            'notification_no' => $notificationNo,
            'exam_date' => $examDate,
            'exam_data' => $exam_data,
            'session' => $session,
            'category' => $category,
            'district' => $district_name ?? 'N/A',
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
        $filename = 'attendance_report_district_' . ($district_name ?? 'all') . '_' . time() . '.pdf';
    
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
    
        // Fetch the exam details
        $exam_data = Currentexam::with('examservice')
            ->where('exam_main_notification', $notificationNo)
            ->first();
    
        if (!$exam_data) {
            return back()->with('error', 'Exam data not found.');
        }
    
        $exam_id = $exam_data->exam_main_no;
    
        // Fetch all confirmed halls for the given exam date
        $examconfirmed_halls = ExamConfirmedHalls::with([
            'district',
            'center',
            'venue',
            'chiefInvigilator.venue',
            'ciCandidateLogs' // Include candidate logs for attendance data
        ])
            ->where('exam_id', $exam_id)
            ->where('exam_date', $examDate)
            ->get();
    
        // Fetch candidate attendance data
        $candidate_attendance_logs = CICandidateLogs::where('exam_id', $exam_id)
            ->where('exam_date', $examDate)
            ->with('ci')
            ->get()
            ->keyBy(function ($log) {
                return $log->ci_id . '-' . $log->hall_code; // Key by CI ID and Hall Code for quick lookup
            });
    
        $session_data = [];
    
        foreach ($examconfirmed_halls as $hall) {
            $district = $hall->district;
            $center = $hall->center;
            $venue = $hall->venue ?? $hall->chiefInvigilator->venue ?? null;
    
            foreach (['FN', 'AN'] as $sessionKey) {
                if ($session && $session !== $sessionKey) {
                    continue; // Skip if session doesn't match the requested session
                }
    
                // Default attendance data
                $attendance_data = [
                    'present' => 0,
                    'absent' => 0,
                    'alloted_count' => $hall->alloted_count ?? 0, // Use hall's allotted count if available
                    'timestamp' => 'N/A'
                ];
    
                // Check if attendance log exists for this hall
                $logKey = $hall->ci_id . '-' . $hall->hall_code;
                if (isset($candidate_attendance_logs[$logKey]) && $candidate_attendance_logs[$logKey]->candidate_attendance) {
                    $decoded_attendance = is_string($candidate_attendance_logs[$logKey]->candidate_attendance)
                        ? json_decode($candidate_attendance_logs[$logKey]->candidate_attendance, true)
                        : $candidate_attendance_logs[$logKey]->candidate_attendance;
    
                    $attendance_data = array_merge($attendance_data, $decoded_attendance[$sessionKey] ?? []);
                }
    
                // Create a session entry
                $sessionEntry = [
                    'session' => $sessionKey,
                    'district_name' => $district->district_name ?? 'N/A',
                    'center_code' => $center->center_code ?? 'N/A',
                    'center_name' => $center->center_name ?? 'N/A',
                    'hall_code' => $hall->hall_code ?? 'N/A',
                    'hall_name' => $venue->venue_name ?? 'N/A',
                    'present' => $attendance_data['present'] ?? 0,
                    'absent' => $attendance_data['absent'] ?? 0,
                    'total_candidates' => $attendance_data['alloted_count'] ?? 0,
                    'percentage' => isset($attendance_data['alloted_count']) && $attendance_data['alloted_count'] > 0
                        ? number_format(($attendance_data['present'] / $attendance_data['alloted_count']) * 100, 2) . '%'
                        : '0%',
                    'timestamp' => $attendance_data['timestamp'] ?? 'N/A',
                ];
    
                $session_data[] = $sessionEntry;
            }
        }
    
        // Sort session data by center_code and hall_code
        usort($session_data, function ($a, $b) {
            return [$a['center_code'], $a['hall_code']] <=> [$b['center_code'], $b['hall_code']];
        });
    
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
