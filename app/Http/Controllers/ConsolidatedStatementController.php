<?php

namespace App\Http\Controllers;

use Spatie\Browsershot\Browsershot;
use App\Models\Currentexam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Center;
use App\Models\CIChecklistAnswer;
use App\Models\ExamConfirmedHalls;
use App\Models\ExamMaterialsData;
use App\Models\ChiefInvigilator;
use App\Models\Scribe;
use App\Models\QpBoxLog;
use App\Models\CICandidateLogs;
use App\Models\CIStaffAllocation;
use App\Models\CIPaperReplacements;
use App\Models\CIChecklist;
use Illuminate\Support\Facades\Auth;
use App\Models\District;

class ConsolidatedStatementController extends Controller
{
    public function index()
    {
        $districts = District::all(); // Fetch all districts
        // Fetch unique center values from the same table
        $centers = center::all(); // Fetch all venues
        return view('view_report.consolidated_statement_report.index', compact('districts', 'centers')); // Path matches the file created
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

    public function filterConsolidatedStatement(Request $request)
    {
        $query = Currentexam::with('examservice');

        // Filter by Notification No
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
                'statement_data' => [] // Ensure empty array is passed
            ]);
        }

        // ✅ Always define `$statement_data`
        $statement_data = [];

        // Fetch Chief Invigilator details along with required fields
        $ci_data = CIChecklistAnswer::where('exam_id', $exam_main_no)
            ->with([
                'center.district', // Ensure center and district relationships exist
                'venue'            // Ensure venue relationship exists
            ])
            ->get();

        if ($ci_data->isNotEmpty()) {
            // Extract required fields
            $statement_data = $ci_data->map(function ($item) {
                // Fetch Chief Invigilator details for each `ci_id`
                $ci_data_details = ChiefInvigilator::where('ci_id', $item->ci_id)
                    ->with(['venue'])
                    ->first();

                return [
                    'exam_id' => $item->exam_id ?? 'N/A',
                    'district' => optional($item->center)->district->district_name ?? 'N/A',
                    'center' => optional($item->center)->center_name . ' - ' . optional($item->center)->center_code ?? 'N/A',
                    'center_code' => optional($item->center)->center_code ?? 'N/A',
                    'hall_code' => $item->hall_code ?? 'N/A',
                    'venue_name' => optional($ci_data_details->venue)->venue_name ?? 'N/A',
                    'ci_id' => optional($ci_data_details)->ci_id ?? 'N/A',
                    'ci_name' => optional($ci_data_details)->ci_name ?? 'N/A',
                    'ci_phone' => optional($ci_data_details)->ci_phone ?? 'N/A',
                    'ci_email' => optional($ci_data_details)->ci_email ?? 'N/A',
                ];
            })->toArray();
        }

        // ✅ Capture the session input from the request
        $session = $request->input('session', ''); // Default empty if not provided
        $exam_date = $request->input('exam_date', '');
        return view('view_report.consolidated_statement_report.index', compact(
            'exam_data',
            'exam_main_no',
            'statement_data',
            'session', // Pass session to the Blade view
            'exam_date'
        ));
    }
}
