<?php

namespace App\Http\Controllers;

use Spatie\Browsershot\Browsershot;
use App\Models\Currentexam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Center;
use App\Models\CIcandidateLogs;
use Illuminate\Support\Facades\Auth;
use App\Models\District;

class CandidateRemarksController extends Controller
{
    public function index()
    {
        $districts = District::all(); // Fetch all districts
        // Fetch unique center values from the same table
        $centers = center::all(); // Fetch all venues
        return view('view_report.candidate_remarks_report.index', compact('districts', 'centers')); // Path matches the file created
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
    public function generateCandidateRemarksReportOverall(Request $request)
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
    
        // Fetch candidate attendance data
        $candidate_attendance = CIcandidateLogs::where('exam_id', $exam_id)
            ->where('exam_date', $examDate)
            ->when($districtId, function ($query) use ($districtId) {
                return $query->whereHas('center.district', function ($q) use ($districtId) {
                    $q->where('id', $districtId);
                });
            })
            ->when($centerId, function ($query) use ($centerId) {
                return $query->whereHas('center', function ($q) use ($centerId) {
                    $q->where('id', $centerId);
                });
            })
            ->with('ci', 'center.district') 
            ->get();
    
        if ($candidate_attendance->isEmpty()) {
            return back()->with('error', 'No candidate attendance found for this exam date.');
        }
    
        // Extract candidate remarks and details
        $candidate_details = [];
    
        foreach ($candidate_attendance as $attendance) {
            // Decode JSON from candidate_remarks
            $remarks_data = $attendance->candidate_remarks;
    
            // Ensure decoding is successful and the session exists
            if (is_array($remarks_data) && isset($remarks_data[$session])) {
                foreach ($remarks_data[$session] as $remark_entry) {
                    $candidate_details[] = [
                        'reg_no' => $remark_entry['registration_number'] ?? 'N/A',
                        'remark' => $remark_entry['remark'] ?? 'N/A',
                        'hall_code' => $attendance->hall_code ?? 'N/A',
                        'district' => $attendance->center->district->district_name ?? 'N/A',
                        'center' => $attendance->center->center_name ?? 'N/A',
                        'center_code' => $attendance->center->center_code ?? 'N/A',
                        'venue_name' => $attendance->ci->venue->venue_name ?? 'N/A',
                    ];
                }
            }
        }
    
        if (empty($candidate_details)) {
            return back()->with('error', 'No candidate remarks found for this session.');
        }
    
        // Prepare final data for Blade
        $data = [
            'notification_no' => $notificationNo,
            'exam_date' => $examDate,
            'exam_data' => $exam_data,
            'session' => $session,
            'category' => $category,
            'candidate_details' => $candidate_details,
        ];
    
        // Debugging output (You can remove this after testing)
        // dd($data);
    
        // Render the Blade template
        $html = view('view_report.candidate_remarks_report.candidate_remarks_report_overall', $data)->render();
    
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
        $filename = 'candidate_remarks_report_overall_' . time() . '.pdf';
    
        // Return the PDF as a response
        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
    
}
