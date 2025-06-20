<?php

namespace App\Http\Controllers;

use App\Models\ExamConfirmedHalls;
use Spatie\Browsershot\Browsershot;
use App\Models\Currentexam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Center;
use App\Models\CIMeetingQrcode;
use App\Models\CIMeetingAttendance;
use Illuminate\Support\Facades\Auth;
use App\Models\District;

class CiMeetingAttendanceController extends Controller
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
        return view('view_report.ci_meeting_report.index', compact('districts', 'centers')); // Path matches the file created
    }
    public function getDropdownData(Request $request)
    {
        $notificationNo = $request->query('notification_no');

        // Check if notification exists
        $examDetails = Currentexam::where('exam_main_notification', $notificationNo)->first();

        if (!$examDetails) {
            // return back()->with('error', 'Invalid Notification No.');
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
    public function generateCIMeetingReport(Request $request)
    {
        $notification_no = $request->input('notification_no');
        $districtId = $request->input('district');
        $centerId = $request->input('center');

        // Retrieve exam data
        $exam_data = Currentexam::with('examservice')
            ->where('exam_main_notification', $notification_no)
            ->first();

        if (!$exam_data) {
            return back()->with('error', 'Exam data not found.');
        }

        $exam_id = $exam_data->exam_main_no;

        // Fetch all confirmed halls (similar to attendance report pattern)
        $examconfirmed_halls = ExamConfirmedHalls::with([
            'district',
            'center',
            'venue',
            'chiefInvigilator.venue',
            'ciCandidateLogs'
        ])
            ->where('exam_id', $exam_id)
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
            ->get()
            ->unique('ci_id')
            ->sortBy('center.center_code')
            ->sortBy('hall_code');  // Keep only one entry per CI

        // Retrieve CI Meeting Attendance data and key by ci_id for quick lookup
        $ci_meeting_attendance = CIMeetingAttendance::where('exam_id', $exam_data->exam_main_no)
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
            ->with([
                'ci',
                'ci.venue',
                'center.district',
            ])
            ->get()
            ->keyBy('ci_id'); // Key by ci_id for quick lookup

        // Extract district codes from confirmed halls  
        $districtCodes = $examconfirmed_halls->pluck('center.district.district_code')->unique()->filter();

        // Retrieve CI Meeting times for each district
        $ci_meeting_times = CIMeetingQrcode::where('exam_id', $exam_data->exam_main_no)
            ->whereIn('district_code', $districtCodes)
            ->get()
            ->keyBy('district_code');

        // Create merged records for all confirmed halls
        $merged_records = collect();

        foreach ($examconfirmed_halls as $hall) {
            // Check if CI meeting attendance exists for this CI
            if (isset($ci_meeting_attendance[$hall->ci_id])) {
                // Use existing attendance record
                $record = $ci_meeting_attendance[$hall->ci_id];
            } else {
                // Create a fake record with the same structure for CIs who didn't attend
                $record = (object) [
                    'ci_id' => $hall->ci_id,
                    'hall_code' => $hall->hall_code,
                    'center' => $hall->center,
                    'ci' => $hall->chiefInvigilator,
                    'created_at' => null, // No attendance
                    'updated_at' => null, // No attendance
                ];
            }

            $merged_records->push($record);
        }

        if ($merged_records->isEmpty()) {
            return back()->with('error', 'No CI data found.');
        }

        // Group merged records by district (maintaining your existing structure)
        $grouped_data = $merged_records->groupBy(function ($item) {
            return $item->center->district->district_name ?? 'Unknown District';
        })->map(function ($items) use ($ci_meeting_times) {
            $districtCode = $items->first()->center->district->district_code ?? null;
            return [
                'ci_meeting_records' => $items,
                'meeting_time' => isset($districtCode) && isset($ci_meeting_times[$districtCode])
                    ? [
                        'meeting_date' => optional($ci_meeting_times[$districtCode])->meeting_date_time
                            ? date('d-m-Y', strtotime($ci_meeting_times[$districtCode]->meeting_date_time))
                            : 'N/A',
                        'meeting_time' => optional($ci_meeting_times[$districtCode])->meeting_date_time
                            ? date('h:i A', strtotime($ci_meeting_times[$districtCode]->meeting_date_time))
                            : 'N/A'
                    ]
                    : null,
            ];
        });

        $exam_name = $exam_data->exam_main_name ?? 'N/A';
        $exam_services = $exam_data->examservice->examservice_name ?? 'N/A';

        // Render the view (keeping your existing structure)
        $html = view('view_report.ci_meeting_report.ci-meeting-report', compact('exam_services', 'exam_name', 'notification_no', 'grouped_data'))->render();

        // Generate PDF using Browsershot
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

        $filename = 'ci-meeting-attendance-report-' . time() . '.pdf';

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}
