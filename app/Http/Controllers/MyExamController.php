<?php

namespace App\Http\Controllers;

use App\Models\Currentexam;
use App\Models\ExamSession;
use App\Models\ExamVenueConsent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MyExamController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
    }

    public function index()
    {
        $exams = Currentexam::all(); // Fetch all exams with their related exam sessions
        return view('my_exam.index', compact('exams')); // Pass the exam to
    }
    public function task($examId)
    {
        $session = Currentexam::with('examsession')->where('exam_main_no', $examId)->first();

        if (!$session) {
            abort(404, 'Session not found');
        }
        // Fetch the audit details for the exam
        $auditDetails = DB::table('exam_audit_logs')
            ->where('exam_id', $examId)
            ->orderBy('created_at', 'asc')
            ->get();
        //get exam venue consent data from venue id
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;
        // if user role is venue user
        $venueConsents = null;
        if ($role == 'venue') {
            $venueConsents = ExamVenueConsent::where('exam_id', $examId)
                ->where('venue_id', $user->venue_id)
                ->first();
            $venueConsents->venueName = $user->venue_name;
            if (!$venueConsents) {
                abort(404, 'Venue consent not found');
            }
        }

        return view('my_exam.task', compact('session', 'auditDetails', 'venueConsents'));
    }
    public function MyTaskAction($examId)
    {
        $role = session('auth_role');
        if ($role == 'center') {
            return $this->centerTask();
        } else if ($role == 'ci') {
            return $this->ciTask($examId);
        } else if ($role == 'mobile_team_staffs') {
            return $this->mobileTeamTask($examId);
        }
        return abort(403, 'Unauthorized access');
    }

    public function centerTask()
    {
        return view('my_exam.center.task');
    }
    public function ciTask($examId)
    {
        $session = Currentexam::with('examsession')->where('exam_main_no', $examId)->first();

        if (!$session) {
            abort(404, 'Exam not found');
        }
        // Group exam sessions by date
        $groupedSessions = $session->examsession->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->exam_sess_date)->format('d-m-Y');
        });

        return view('my_exam.CI.task', compact('session', 'groupedSessions'));

    }
    public function mobileTeamTask($examId)
    {
        $session = Currentexam::with('examsession')->where('exam_main_no', $examId)->first();

        if (!$session) {
            abort(404, 'Exam not found');
        }
        // Fetch the audit details for the exam
        $auditDetails = DB::table('exam_audit_logs')
            ->where('exam_id', $examId)
            ->orderBy('created_at', 'asc')
            ->get();
        return view('my_exam.MobileTeam.task', compact('auditDetails', 'session'));
    }

}
