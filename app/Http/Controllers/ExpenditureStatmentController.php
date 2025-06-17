<?php

namespace App\Http\Controllers;

use App\Models\Currentexam;
use App\Models\ExamConfirmedHalls;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Models\Center;
use App\Models\CIChecklistAnswer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\District;

use Illuminate\Http\Request;

class ExpenditureStatmentController extends Controller
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
        return view('view_report.expenditure_report.index', compact('districts', 'centers')); // Path matches the file created
    }

    public function filterExpenditure(Request $request)
    {
        $query = Currentexam::with('examservice');

        // Apply filters if the input is provided
        if ($request->filled('notification_no')) {
            $query->where('exam_main_notification', $request->notification_no);
        }

        // Fetch the filtered results
        $exam_data = $query->get();

        // Ensure there's at least one record before accessing properties
        if ($exam_data->isNotEmpty()) {
            $exam_main_no = $exam_data->first()->exam_main_no;
        } else {
            return redirect()->back()->with([
                'error' => 'No exam data found.',
                'attendance_data' => [] // Pass empty array to avoid errors
            ]);
        }

        // ✅ Always define `$attendance_data` before using it
        $attendance_data = [];

        // Fetch candidate attendance and related data
        $candidate_attendance = CIChecklistAnswer::where('exam_id', $exam_main_no)
            ->where('utility_answer', '!=', '[]') // ✅ Skip empty array values
            ->with('ci.venue', 'center.district') // Include relationships
            ->get();

        if ($candidate_attendance->isNotEmpty()) {
            // Extract required fields
            $attendance_data = $candidate_attendance->map(function ($item) {
                // Decode utility_answer JSON safely
                $utility = $item->utility_answer;

                // Generate encrypted URL for each record
                $encryptedUrl = Crypt::encryptString('storage/reports/' . $item->exam_id . '/utilization-report/utilization-report-' . optional($item->center->district)->district_code . '-' . optional($item->center)->center_code . '-' . optional($item->ci->venue)->venue_code . '-' . $item->hall_code . '.pdf');

                return [
                    'id' => $item->id,
                    'district' => optional($item->center->district)->district_name ?? 'N/A',
                    'district_code' => optional($item->center->district)->district_code ?? 'N/A',
                    'center' => optional($item->center)->center_name ?? 'N/A',
                    'center_code' => optional($item->center)->center_code ?? 'N/A',
                    'hall_code' => $item->hall_code ?? 'N/A',
                    'venue_name' => optional($item->ci->venue)->venue_name ?? 'N/A',
                    'venue_code' => optional($item->ci->venue)->venue_code ?? 'N/A',
                    'amountReceived' => $utility['amountReceived'] ?? '0',
                    'totalAmountSpent' => $utility['totalAmountSpent'] ?? '0',
                    'balanceAmount' => $utility['balanceAmount'] ?? '0',
                    'encryptedUrl' => $encryptedUrl, // Pass encrypted URL
                ];
            })->toArray(); // Ensure it's an array
        }

        // ✅ Now `$attendance_data` is always defined (empty or filled)
        return view('view_report.expenditure_report.index', compact(
            'exam_data',
            'exam_main_no',
            'attendance_data' // This will never be undefined
        ));
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

    public function generateExpenditureReportOverall(Request $request)
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
        // Retrieve CI Utility data and key by ci_id for quick lookup
        $CIChecklistAnswer = CIChecklistAnswer::where('exam_id', $exam_data->exam_main_no)
            ->where('utility_answer', '!=', '[]') // Ensure utility_answer is not empty
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


        // Create merged records for all confirmed halls
        $merged_records = collect();

        foreach ($examconfirmed_halls as $hall) {
            // Check if CI Utitlity exists for this CI
            if (isset($CIChecklistAnswer[$hall->ci_id])) {
                // Use existing attendance record
                $record = $CIChecklistAnswer[$hall->ci_id];
            } else {
                // Create a fake record with the same structure for CIs who didn't attend
                $record = (object) [
                    'ci_id' => $hall->ci_id,
                    'hall_code' => $hall->hall_code,
                    'center' => $hall->center,
                    'ci' => $hall->chiefInvigilator,
                    'utility_answer' => null, // No utility data
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
        })->map(function ($items) {

            return [
                'ci_utility_records' => $items,

            ];
        });
     

        // Render the view (keeping your existing structure)
        $html = view('view_report.expenditure_report.expenditure-report-overall', compact('exam_data', 'notification_no', 'grouped_data'))->render();

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

        $filename = 'ci-expenditure-report-grouped-overall-' . time() . '.pdf';

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}
