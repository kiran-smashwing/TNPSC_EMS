<?php

namespace App\Http\Controllers;

use App\Models\CIMeetingQrcode;
use App\Models\Currentexam;
use App\Models\ExamTrunkBoxOTLData;
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
        // Retrieve the current exam session
        $session = Currentexam::with('examsession')->where('exam_main_no', $examId)->first();

        if (!$session) {
            abort(404, 'Exam not found');
        }

        // Get the role and user from the session
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        if (!$user) {
            abort(403, 'Unauthorized action.');
        }

        $ci_id = $user->ci_id;

        // Retrieve CI meeting data
        $ci_meeting_Data = DB::table('ci_meeting_attendance')
            ->where('exam_id', $examId)
            ->where('ci_id', $ci_id)
            ->get();

        $adequacy_check_data = [];
        $firstReceivedAmount = null;

        if ($ci_meeting_Data->isNotEmpty()) {
            // Loop through each meeting entry
            foreach ($ci_meeting_Data as $meetingData) {
                // Decode the 'adequacy_check' JSON field
                $adequacyData = json_decode($meetingData->adequacy_check, true);

                // Check if the required keys are present
                if (isset($adequacyData['exam_id'])) {
                    // Extract relevant data for the output
                    $adequacy_check_data[] = [
                        'exam_id' => $adequacyData['exam_id'] ?? 'N/A',
                        'received_amount' => $adequacyData['received_amount'] ?? 'N/A',
                        'received_packet' => $adequacyData['received_packet'] ?? 'N/A',
                        'received_appointment_letter' => $adequacyData['received_appointment_letter'] ?? 'N/A',
                    ];
                }
            }

            // Extract 'received_amount' from the first item, if available
            if (!empty($adequacy_check_data)) {
                $firstReceivedAmount = $adequacy_check_data[0]['received_amount'] ?? null;
            }
        }

        // Group exam sessions by date
        $groupedSessions = $session->examsession->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->exam_sess_date)->format('d-m-Y');
        });

        // Retrieve preliminary checklist
        $preliminary = CIChecklist::where('ci_checklist_type', 'Preliminary')->get();

        // Pass all data to the view
        return view('my_exam.CI.task', compact('session', 'groupedSessions', 'preliminary', 'adequacy_check_data', 'firstReceivedAmount'));
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
        $checklist_videography_data = [];
        $candidate_orm_remarks_data = []; // For storing
        // Initialize attendance data arrays
        $candidate_attendance_data = [
            'absent' => $candidateAttendance['absent'] ?? 0,
            'present' => $candidateAttendance['present'] ?? 0,
            'alloted_count' => $candidateAttendance['alloted_count'] ?? 0,
        ];


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


        // Retrieve the checklist videography data
        $checklistvideographyData = DB::table('ci_checklist_answers')
            ->where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->get();

        // Initialize an array to hold all checklist data


        if ($checklistvideographyData->isNotEmpty()) {
            // Loop through each checklist entry
            foreach ($checklistvideographyData as $checklistAnswer) {
                // Decode the 'videography_answer' JSON field
                $videographyData = json_decode($checklistAnswer->videography_answer, true);

                // Check if 'sessions' is set in the decoded data
                if (isset($videographyData['sessions'])) {
                    // Loop through the sessions
                    foreach ($videographyData['sessions'] as $sessionData) {
                        // Ensure session type (AN or FN) and match with the current session
                        if (isset($sessionData['session'])) {
                            // Check if the session matches the current session (AN or FN)
                            if (($sessionData['session'] === 'FN' && $session->exam_sess_session == 'FN') ||
                                ($sessionData['session'] === 'AN' && $session->exam_sess_session == 'AN')
                            ) {

                                // Add data to the checklist
                                foreach ($sessionData['checklist'] as $checklistItem) {
                                    $checklist_videography_data[] = [
                                        'checklist_id' => $checklistItem['checklist_id'] ?? 'N/A',
                                        'description' => $checklistItem['description'] ?? 'N/A',
                                        'inspection_staff' => $checklistItem['inspection_staff'] ?? 'N/A',
                                        'exam_date' => $sessionData['exam_date'] ?? 'N/A',
                                        'timestamp' => $sessionData['timestamp'] ?? 'N/A',
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }
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
            $candidateAttendance = $ciCandidatelogs->candidate_attendance
                ? json_decode($ciCandidatelogs->candidate_attendance, true)
                : []; // Decode the `candidate_attendance` field or initialize as an empty array



            // Check if session type is AN or FN and assign the attendance data accordingly
            if ($session->exam_sess_session == 'AN' && isset($candidateAttendance['AN'])) {
                $candidate_attendance_data['absent'] = $candidateAttendance['AN']['absent'] ?? 0;
                $candidate_attendance_data['present'] = $candidateAttendance['AN']['present'] ?? 0;
                $candidate_attendance_data['alloted_count'] = $candidateAttendance['AN']['alloted_count'] ?? 0;
            }

            if ($session->exam_sess_session == 'FN' && isset($candidateAttendance['FN'])) {
                $candidate_attendance_data['absent'] = $candidateAttendance['FN']['absent'] ?? 0;
                $candidate_attendance_data['present'] = $candidateAttendance['FN']['present'] ?? 0;
                $candidate_attendance_data['alloted_count'] = $candidateAttendance['FN']['alloted_count'] ?? 0;
            }

            // Additional processing for logs, remarks, etc.
            $candidateLogs = $ciCandidatelogs->additional_details
                ? json_decode($ciCandidatelogs->additional_details, true)
                : [];

            $candidate_remarks_data = [];
            $candidate_orm_remarks_data = [];

            if ($ciCandidatelogs->candidate_remarks) {
                $candidateRemarks = json_decode($ciCandidatelogs->candidate_remarks, true);

                if (isset($candidateRemarks['AN']) && $session->exam_sess_session == 'AN') {
                    foreach ($candidateRemarks['AN'] as $remark) {
                        $candidate_remarks_data['AN'][] = [
                            'registration_number' => $remark['registration_number'] ?? 'N/A',
                            'remark' => $remark['remark'] ?? 'N/A',
                        ];
                    }
                }

                if (isset($candidateRemarks['FN']) && $session->exam_sess_session == 'FN') {
                    foreach ($candidateRemarks['FN'] as $remark) {
                        $candidate_remarks_data['FN'][] = [
                            'registration_number' => $remark['registration_number'] ?? 'N/A',
                            'remark' => $remark['remark'] ?? 'N/A',
                        ];
                    }
                }
            }

            if ($ciCandidatelogs->omr_remarks) {
                $candidateomrRemarks = json_decode($ciCandidatelogs->omr_remarks, true);

                if (isset($candidateomrRemarks['AN']) && $session->exam_sess_session == 'AN') {
                    foreach ($candidateomrRemarks['AN'] as $remark) {
                        $candidate_orm_remarks_data['AN'][] = [
                            'registration_number' => $remark['registration_number'] ?? 'N/A',
                            'remark' => $remark['remark'] ?? 'N/A',
                        ];
                    }
                }

                if (isset($candidateomrRemarks['FN']) && $session->exam_sess_session == 'FN') {
                    foreach ($candidateomrRemarks['FN'] as $remark) {
                        $candidate_orm_remarks_data['FN'][] = [
                            'registration_number' => $remark['registration_number'] ?? 'N/A',
                            'remark' => $remark['remark'] ?? 'N/A',
                        ];
                    }
                }
            }
        }

        // Process allocations (scribes and assistants)
        if ($existingAllocations->isNotEmpty()) {
            foreach ($existingAllocations as $allocation) {
                // Decode scribes data
                $scribesData = json_decode($allocation->scribes, true) ?? [];
                $assistantsData = json_decode($allocation->assistants, true) ?? []; // Decode assistants data

                // Process scribes data
                // Process scribes data
                foreach ($scribesData as $scribeData) {
                    if (isset($scribeData['session'])) {
                        $sessionType = $scribeData['session'];

                        // Check session type: FN or AN
                        if ($sessionType == 'FN' && $session->exam_sess_session == 'FN') {
                            // Ensure 'data' key exists in the data
                            if (isset($scribeData['data']) && is_array($scribeData['data'])) {
                                foreach ($scribeData['data'] as $data) {
                                    // Ensure 'scribe' key exists in the data
                                    if (isset($data['scribe'])) {
                                        $scribeId = $data['scribe'];  // Get scribe ID

                                        // Fetch scribe based on the scribe_id (correct column)
                                        $scribe = Scribe::where('scribe_id', $scribeId)->first();

                                        // Add scribe details to the result
                                        if ($scribe) {
                                            $scribes_type[] = [
                                                'scribe_name' => $scribe->scribe_name,
                                                'scribe_phone' => $scribe->scribe_phone,
                                                'reg_no' => $data['reg_no'] ?? 'Not Available',
                                            ];
                                        }
                                    }
                                }
                            }
                        } elseif ($sessionType == 'AN' && $session->exam_sess_session == 'AN') {
                            // Ensure 'data' key exists in the data
                            if (isset($scribeData['data']) && is_array($scribeData['data'])) {
                                foreach ($scribeData['data'] as $data) {
                                    // Ensure 'scribe' key exists in the data
                                    if (isset($data['scribe'])) {
                                        $scribeId = $data['scribe'];  // Get scribe ID

                                        // Fetch scribe based on the scribe_id (correct column)
                                        $scribe = Scribe::where('scribe_id', $scribeId)->first(); // Fixed column name

                                        // Add scribe details to the result
                                        if ($scribe) {
                                            $scribes_type[] = [
                                                'scribe_name' => $scribe->scribe_name,
                                                'scribe_phone' => $scribe->scribe_phone,
                                                'reg_no' => $data['reg_no'] ?? 'Not Available',
                                            ];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }


                // dd($scribes_type);
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
        // Retrieve the allocation data for the CI (Chief Invigilator)
        $existingAllocation = DB::table('ci_staff_allocation')
            ->where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)  // Ensure the user is checked as well
            ->first();

        if ($existingAllocation) {
            // Decode the invigilators data (assuming it's stored as a JSON string)
            $sessionData = json_decode($existingAllocation->invigilators, true);  // Convert JSON to array

            // Loop through the session data to check session type (FN or AN)
            foreach ($sessionData as $data) {
                if (isset($data['session'])) {
                    $sessionType = $data['session'];  // "FN" or "AN"

                    // Check if the current session type matches the one stored in the database
                    if ($sessionType == 'FN' && $session->exam_sess_session == 'FN') {
                        // Handle logic for FN session
                        $invigilators_type = $data['invigilators']; // Array of invigilator IDs for FN session
                    } elseif ($sessionType == 'AN' && $session->exam_sess_session == 'AN') {
                        // Handle logic for AN session
                        $invigilators_type = $data['invigilators']; // Array of invigilator IDs for AN session
                    }
                }
            }
        }
        //  dd($invigilators_type);
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
        // dd($invigilator);
        $scribe = Scribe::where('scribe_venue_id', $user->ci_venue_id)->get();
        $ci_assistant = CIAssistant::where('cia_venue_id', $user->ci_venue_id)->get();

        // Retrieve checklist sessions
        $type_sessions = CIChecklist::where('ci_checklist_type', 'Session')->get();
        $consolidate_data = CIChecklist::where('ci_checklist_type', 'Self Declaration')->get();
        // dd($consolidate_data);

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
            'candidate_logs_data',
            'cipaperreplacement_data',
            'candidate_remarks_data',
            'checklist_videography_data',
            'candidate_orm_remarks_data',
            'consolidate_data',
            'candidate_attendance_data',
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
