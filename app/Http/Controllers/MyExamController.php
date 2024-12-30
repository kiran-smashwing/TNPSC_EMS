<?php

namespace App\Http\Controllers;

use App\Models\CIMeetingQrcode;
use App\Models\Currentexam;
use App\Models\CIAssistant;
use App\Models\Invigilator;
use App\Models\ExamAuditLog;
use App\Models\ExamSession;
use App\Models\ExamVenueConsent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\CIChecklist;
use App\Models\ExamConfirmedHalls;
use App\Models\Scribe;

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

        return view('my_exam.task', compact('session', 'auditDetails', 'venueConsents', 'meetingCodeGen', 'examMaterialsUpdate'));
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
        } else {
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

        // Check if session is found
        if (!$session) {
            abort(404, 'Session not found');
        }

        // Get the role and user from the session
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        if (!$user) {
            abort(403, 'Unauthorized action.');
        }

        $ci_id = $user->ci_id;

        // Initialize variables
        $invigilators_type = [];
        $scribes_type = [];
        $assistants_type = [];
        $candidate_logs_data = [];
        $cipaperreplacement_data = [];
        $candidate_remarks_data = []; // For storing remarks data

        // Retrieve allocation records
        $existingAllocations = DB::table('ci_staff_allocation')
            ->where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->get();

        // Retrieve candidate logs data for the CI user
        $ciCandidatelogs = DB::table('ci_candidate_logs')
            ->where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->first();

        $cipaperreplacement = DB::table('ci_paper_replacements')
            ->where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->get();

        // Separate cipaperreplacement by session
        foreach ($cipaperreplacement as $replacement) {
            $sessionType = $replacement->exam_session; // Assuming 'exam_session' is a column in the table

            // Only add the data, no session titles
            if ($sessionType === 'FN' && $session->exam_sess_session == 'FN') {
                $cipaperreplacement_data[] = $replacement;
            } elseif ($sessionType === 'AN' && $session->exam_sess_session == 'AN') {
                $cipaperreplacement_data[] = $replacement;
            }
        }

        // Decode candidate logs and remarks
        if ($ciCandidatelogs) {
            $candidateLogs = json_decode($ciCandidatelogs->additional_details, true); // Assuming 'candidate_logs' is the JSON field

            // Separate the candidate logs by session type
            if (isset($candidateLogs['AN']) && $session->exam_sess_session == 'AN') {
                $candidate_logs_data['AN'] = [];
                foreach ($candidateLogs['AN'] as $log) {
                    $candidate_logs_data['AN'][] = [
                        'registration_number' => $log['registration_number'] ?? 'N/A',
                        'candidate_name' => $log['candidate_name'] ?? 'N/A',
                    ];
                }
            }

            if (isset($candidateLogs['FN']) && $session->exam_sess_session == 'FN') {
                $candidate_logs_data['FN'] = [];
                foreach ($candidateLogs['FN'] as $log) {
                    $candidate_logs_data['FN'][] = [
                        'registration_number' => $log['registration_number'] ?? 'N/A',
                        'candidate_name' => $log['candidate_name'] ?? 'N/A',
                    ];
                }
            }

            // Decode candidate remarks
            if (isset($ciCandidatelogs->candidate_remarks)) {
                $candidateRemarks = json_decode($ciCandidatelogs->candidate_remarks, true);

                // Separate the candidate remarks by session type (AN and FN)
                if (isset($candidateRemarks['AN']) && $session->exam_sess_session == 'AN') {
                    $candidate_remarks_data['AN'] = [];
                    foreach ($candidateRemarks['AN'] as $remark) {
                        $candidate_remarks_data['AN'][] = [
                            'registration_number' => $remark['registration_number'] ?? 'N/A',
                            // 'candidate_name' => $remark['candidate_name'] ?? 'N/A',
                            'remark' => $remark['remark'] ?? 'N/A', // Add the remark field
                        ];
                    }
                }

                if (isset($candidateRemarks['FN']) && $session->exam_sess_session == 'FN') {
                    $candidate_remarks_data['FN'] = [];
                    foreach ($candidateRemarks['FN'] as $remark) {
                        $candidate_remarks_data['FN'][] = [
                            'registration_number' => $remark['registration_number'] ?? 'N/A',
                            // 'candidate_name' => $remark['candidate_name'] ?? 'N/A',
                            'remark' => $remark['remark'] ?? 'N/A', // Add the remark field
                        ];
                    }
                }
            }
        }
    //    dd($candidate_remarks_data);
        // Process allocations (scribes and assistants)
        if ($existingAllocations->isNotEmpty()) {
            foreach ($existingAllocations as $allocation) {
                // Decode scribes data
                $scribesData = json_decode($allocation->scribes, true) ?? [];
                $assistantsData = json_decode($allocation->assistants, true) ?? []; // Decode assistants data

                // Process scribes data
                foreach ($scribesData as $scribeData) {
                    if (isset($scribeData['session'])) {
                        $sessionType = $scribeData['session'];

                        // Check session type: FN or AN
                        if ($sessionType == 'FN' && $session->exam_sess_session == 'FN') {
                            $scribes = Scribe::whereIn('scribe_id', $scribeData['scribes'])->get();
                            foreach ($scribes as $scribe) {
                                $scribes_type[] = [
                                    'scribe_name' => $scribe->scribe_name,
                                    'scribe_phone' => $scribe->scribe_phone,
                                    'reg_no' => $scribeData['reg_no'] ?? 'Not Available',
                                ];
                            }
                        } elseif ($sessionType == 'AN' && $session->exam_sess_session == 'AN') {
                            $scribes = Scribe::whereIn('id', $scribeData['scribes'])->get();
                            foreach ($scribes as $scribe) {
                                $scribes_type[] = [
                                    'scribe_name' => $scribe->scribe_name,
                                    'scribe_phone' => $scribe->scribe_phone,
                                    'reg_no' => $scribeData['reg_no'] ?? 'Not Available',
                                ];
                            }
                        }
                    }
                }

                // Process assistants data
                foreach ($assistantsData as $assistantData) {
                    if (isset($assistantData['session'])) {
                        $sessionType = $assistantData['session'];

                        // Check session type: FN or AN
                        if ($sessionType == 'FN' && $session->exam_sess_session == 'FN') {
                            $assistants = CIAssistant::whereIn('cia_id', $assistantData['assistants'])->get();
                            foreach ($assistants as $assistant) {
                                $assistants_type[] = [
                                    'assistant_name' => $assistant->cia_name,
                                    'assistant_phone' => $assistant->cia_phone,
                                    'timestamp' => $assistantData['timestamp'] ?? 'Not Available',
                                ];
                            }
                        } elseif ($sessionType == 'AN' && $session->exam_sess_session == 'AN') {
                            $assistants = CIAssistant::whereIn('cia_id', $assistantData['assistants'])->get();
                            foreach ($assistants as $assistant) {
                                $assistants_type[] = [
                                    'assistant_name' => $assistant->cia_name,
                                    'assistant_phone' => $assistant->cia_phone,
                                    'timestamp' => $assistantData['timestamp'] ?? 'Not Available',
                                ];
                            }
                        }
                    }
                }
            }
        }

        // Retrieve session type (Objective or Descriptive)
        $session_type = $session->exam_sess_type;

        // Get confirmed halls for the session
        $session_confirmedhalls = ExamConfirmedHalls::where('exam_id', $examId)
            ->where('exam_session', $session->exam_sess_session)
            ->where('exam_date', $session->exam_sess_date)
            ->where('ci_id', $user->ci_id)
            ->first();

        $alloted_count = $session_confirmedhalls
            ? ($session_type == 'Objective'
                ? $session_confirmedhalls->alloted_count / 20
                : $session_confirmedhalls->alloted_count / 10)
            : 0;

        // Retrieve invigilators, scribes, and assistants based on the venue
        $invigilator = Invigilator::where('invigilator_venue_id', $user->ci_venue_id)->get();
        $scribe = Scribe::where('scribe_venue_id', $user->ci_venue_id)->get();
        $ci_assistant = CIAssistant::where('cia_venue_id', $user->ci_venue_id)->get();

        // Retrieve checklist sessions
        $type_sessions = CIChecklist::where('ci_checklist_type', 'Session')->get();

        // Return the view with the data
        return view('my_exam.CI.ci-exam-activity', compact(
            'ci_assistant',
            'session',
            'type_sessions',
            'invigilator',
            'session_type',
            'session_confirmedhalls',
            'alloted_count',
            'invigilators_type',
            'scribes_type',
            'assistants_type',
            'scribe',
            'candidate_logs_data', // Include candidate logs for both AN and FN
            'cipaperreplacement_data', // Separate FN and AN data
            'candidate_remarks_data', // Include candidate remarks data
            'ci_id'
        ));
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
