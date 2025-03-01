<?php

namespace App\Http\Controllers;

use App\Models\Currentexam;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\DB;
use App\Models\Center;
use App\Models\CIChecklistAnswer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\District;

use Illuminate\Http\Request;

class Expenditure_StatmentController extends Controller
{
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
            ->with('ci.venue', 'center.district') // Include relationships
            ->get();

        if ($candidate_attendance->isNotEmpty()) {
            // Extract required fields
            $attendance_data = $candidate_attendance->map(function ($item) {
                // Decode utility_answer JSON safely
                $utility = $item->utility_answer;

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
   
}
