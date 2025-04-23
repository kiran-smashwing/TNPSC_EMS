<?php

namespace App\Http\Controllers;
use App\Models\ExamSession;
use Spatie\Browsershot\Browsershot;
use App\Models\Currentexam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Center;
use Illuminate\Support\Facades\Auth;
use App\Models\District;
use Carbon\Carbon;
use App\Models\AlertNotification;
class ExamMaterialsDiscrepancyController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
    }
    public function index( Request $request )
    {
        $districts = District::all();
        $centers = Center::all();

        // First get the notification number - either from request or default
        $notificationNo = $request->filled('notification_no')
            ? $request->notification_no
            : null;

        // Get current exam based on notification number
        $currentexam = $notificationNo
            ? CurrentExam::where('exam_main_notification', $notificationNo)->first()
            : null;

        // Get the session based on request parameters or default to today's nearest
        if ($request->filled('exam_date') && $request->filled('session')) {
            $session = ExamSession::where('exam_sess_date', $request->exam_date)
                ->where('exam_sess_session', $request->session)
                ->first();
        } else {
            // Default session logic
            $today = Carbon::today();
            $session = ExamSession::query()
                ->whereRaw("TO_DATE(exam_sess_date, 'DD-MM-YYYY') <= ?", [$today->format('Y-m-d')])
                ->orderByRaw("TO_DATE(exam_sess_date, 'DD-MM-YYYY') DESC")
                ->first();
        }

        // Initialize query
        $query = AlertNotification::query();

        // Apply filters based on request parameters
        if ($currentexam) {
            $query->where('exam_id', $currentexam->exam_main_no);
        }

        if ($request->filled('exam_date')) {
            $query->where('exam_date', Carbon::parse($request->exam_date)->format('Y-m-d'));
        } else if ($session) {
            $query->where('exam_date', Carbon::parse($session->exam_sess_date)->format('Y-m-d'));
        }

        if ($request->filled('session')) {
            $query->where('exam_session', $request->session);
        }

        // Handle category-based filtering
        if ($request->filled('category')) {
            switch ($request->category) {
                case 'district':
                    if ($request->filled('district')) {
                        $query->whereHas('district', function ($q) use ($request) {
                            $q->where('district_code', $request->district);
                        });
                    }
                    break;

                case 'center':
                    if ($request->filled('district')) {
                        $query->whereHas('district', function ($q) use ($request) {
                            $q->where('district_code', $request->district);
                        });
                    }
                    if ($request->filled('center')) {
                        $query->whereHas('center', function ($q) use ($request) {
                            $q->where('center_code', $request->center);
                        });
                    }
                    break;
            }
        }
        // Handle alert type-based filtering
        if ($request->filled('alertType')) {
            $query->where('alert_type', $request->alertType);
        }
        $emergencyAlerts = $query->with(['district', 'center', 'ci.venue'])->get();

        // Store filter values for form
        $filters = [
            'notification_no' => $notificationNo ?? ($session->currentexam->exam_main_notification ?? ''),
            'exam_date' => $request->exam_date ?? ($session->exam_sess_date ?? ''),
            'session' => $request->session ?? ($session->exam_sess ?? ''),
            'category' => $request->category,
            'district' => $request->district,
            'center' => $request->center,
            'alertType' => $request->alertType
        ];


        return view(
            'view_report.exam_material_discrepancy.index',
            compact('districts', 'centers', 'session', 'emergencyAlerts', 'filters', 'currentexam')
        );
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
