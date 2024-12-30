<?php

namespace App\Http\Controllers;

use App\Models\CIMeetingQrcode;
use App\Models\Currentexam;
use App\Models\ExamTrunkBoxOTLData;
use App\Models\Invigilator;
use App\Models\ExamAuditLog;
use App\Models\ExamSession;
use App\Models\ExamVenueConsent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\CIChecklist;
use App\Models\ExamConfirmedHalls;

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
        $meetingCodeGen = null;
        if ($role == 'venue') {
            $venueConsents = ExamVenueConsent::where('exam_id', $examId)
                ->where('venue_id', $user->venue_id)
                ->first();
            $venueConsents->venueName = $user->venue_name;
            if (!$venueConsents) {
                abort(404, 'Venue consent not found');
            }
        } else if ($role == 'district') {
            $meetingCodeGen = CIMeetingQrcode::where('exam_id', $examId)
                ->where('district_code', $user->district_code)
                ->first();
            if ($meetingCodeGen !== null) {
                $meetingCodeGen->user = $user;
            }
        }
        $examMaterialsUpdate = null;
        $examMaterialsUpdate = ExamAuditLog::where('exam_id', $examId)
            ->where('task_type', 'ed_exam_materials_qrcode_upload')
            ->first();
        $examTrunkboxOTLData = ExamAuditLog::where('exam_id', $examId)
        ->where('task_type', 'ed_exam_trunkbox_qr_otl_upload')
        ->first();

        return view('my_exam.task', compact('session', 'auditDetails', 'venueConsents', 'meetingCodeGen', 'examMaterialsUpdate', 'examTrunkboxOTLData'));
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
        else {
            return $this->mobileTeamTask($examId);
        }
        // return abort(403, 'Unauthorized access');
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
        $preliminary = CIChecklist::where('ci_checklist_type', 'Preliminary')->get();
        return view('my_exam.CI.task', compact('session', 'groupedSessions', 'preliminary'));
    }
    public function ciExamActivity($examId, $session)
    {
        // Retrieve the session details with related currentexam
        $session = ExamSession::with('currentexam')
            ->where('exam_sess_mainid', $examId)
            ->where('exam_sess_session', $session)
            ->first();

        // $session_exam_no = $session->exam_sess_mainid;
    
        // Check if session is found
        if (!$session) {
            abort(404, 'Session not found');
        }
    
        // Get the role and user from the session
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;
    
        // Retrieve session type (Objective or Descriptive)
        $session_type = $session->exam_sess_type; // Assuming 'exam_sess_type' is the column for exam type    
        // Check the session type and perform specific logic
        if ($session_type == 'Objective') {
            // Fetch confirmed halls for Objective type
            $session_confirmedhalls = ExamConfirmedHalls::where('exam_id', $examId)
                ->where('exam_session', $session->exam_sess_session)
                ->where('exam_date', $session->exam_sess_date)
                ->where('ci_id', $user->ci_id)
                ->first();
    
            // Divide the alloted_count by 2 and assign it to the variable
            $alloted_count = $session_confirmedhalls->alloted_count / 20; // Sum and then divide by 2
    
        } elseif ($session_type == 'Descriptive') {
            // Fetch confirmed halls for Descriptive type
            $session_confirmedhalls = ExamConfirmedHalls::where('exam_id', $examId)
                ->where('exam_session', $session->exam_sess_session)
                ->where('exam_date', $session->exam_sess_date)
                ->where('ci_id', $user->ci_id)
                ->first();
            $alloted_count = $session_confirmedhalls->alloted_count / 10; // Sum and then divide by 2
        }
    
        // For debugging, you can uncomment the line below to check the fetched data
        //  dd($alloted_count);
    
        // Retrieve the invigilators based on the venue
        $invigilator = Invigilator::where('invigilator_venue_id', $user->ci_venue_id)->get();
    
        // Retrieve checklist sessions
        $type_sessions = CIChecklist::where('ci_checklist_type', 'Session')->get();
    
        // Return the view with the data
        return view('my_exam.CI.ci-exam-activity', compact('session', 'type_sessions', 'invigilator', 'session_type', 'session_confirmedhalls', 'alloted_count'));
    }
    


    public function mobileTeamTask($examId)
    {
        $session = Currentexam::with('examsession')->where('exam_main_no', $examId)->first();

        if (!$session) {
            abort(404, 'Exam not found');
        }
        // Group exam sessions by date
        $groupedSessions = $session->examsession->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->exam_sess_date)->format('d-m-Y');
        });
        // Fetch the audit details for the exam
        $auditDetails = DB::table('exam_audit_logs')
            ->where('exam_id', $examId)
            ->orderBy('created_at', 'asc')
            ->get();
        return view('my_exam.MobileTeam.task', compact('auditDetails', 'session', 'groupedSessions'));
    }
}
