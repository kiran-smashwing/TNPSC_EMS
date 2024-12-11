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
        $exams = Currentexam::with('examsession')->get(); // Fetch all exams with their related exam sessions
        return view('my_exam.index', compact('exams')); // Pass the exam to
    }
    public function task($examId, $sessionId)
    {
        $session = ExamSession::with([
            'currentexam.examservice' // Load the exam and its related service
        ])->find($sessionId);

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
    public function centerTask()
    {

        return view('my_exam.center.task');
    }

}
