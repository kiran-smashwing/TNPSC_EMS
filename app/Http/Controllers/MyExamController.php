<?php

namespace App\Http\Controllers;

use App\Models\CICandidateLogs;
use App\Models\CIChecklistAnswer;
use App\Models\CIMeetingQrcode;
use App\Models\CIPaperReplacements;
use App\Models\CIStaffAllocation;
use App\Models\Currentexam;
use App\Models\ExamMaterialRoutes;
use App\Models\ExamMaterialsData;
use App\Models\ExamTrunkBoxOTLData;
use App\Models\CIAssistant;
use App\Models\Invigilator;
use App\Models\ExamAuditLog;
use App\Models\ExamSession;
use App\Models\ExamVenueConsent;
use App\Models\QpBoxLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\CIChecklist;
use App\Models\ExamConfirmedHalls;
use App\Models\Scribe;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
    public function task(Request $request, $examId)
    {
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;
        if ($role == 'ci') {
            return $this->ciTask($examId);
        } else if ($role == 'mobile_team_staffs' || ($role == 'headquarters' && $user->custom_role == 'VDS')) {
            return $this->mobileTeamTask($examId);
        } else {
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

            // if user role is venue user
            $venueConsents = null;
            $meetingCodeGen = null;
            $sendExamVenueConsent = null;
            $receiveMaterialsPrinterToDistrict = null;
            $receiveMaterialsDistrictToCenter = null;
            $receiveMaterialsMobileteamToCenter = null;
            if ($role == 'venue') {
                $venueConsents = ExamVenueConsent::where('exam_id', $examId)
                    ->where('venue_id', $user->venue_id)
                    ->where('consent_status', '!=', 'saved')
                    ->first();
                if (!$venueConsents) {
                    abort(404, 'Venue consent not found');
                }
                $venueConsents->venueName = $user->venue_name;
                $venueConsents->profile_image = $user->venue_image;

            } else {
                $meetingCodeGen = CIMeetingQrcode::where('exam_id', $examId)
                    ->where('district_code', $user->district_code ?? '01')
                    ->first();
                if ($meetingCodeGen !== null) {
                    $meetingCodeGen->user = $user;
                }
            }

            //Fetch Activity Logs
            $expectedCandidatesUpload = ExamAuditLog::where('exam_id', $examId)
                ->where('task_type', 'apd_expected_candidates_upload')
                ->first();
            $candidatesCountIncrease = ExamAuditLog::where('exam_id', $examId)
                ->where('task_type', 'id_candidates_update_percentage')
                ->first();

            $examRoutesCreated = ExamAuditLog::where('exam_id', $examId)
                ->where('task_type', 'exam_material_routes_created')
                ->first();
            $examVenueHallConfirmation = ExamAuditLog::where('exam_id', $examId)
                ->where('task_type', 'exam_venue_hall_confirmation')
                ->first();
            $apdFinalizeHallsUpload = ExamAuditLog::where('exam_id', $examId)
                ->where('task_type', 'apd_finalize_halls_upload')
                ->first();
            $examMaterialsUpdate = ExamAuditLog::where('exam_id', $examId)
                ->where('task_type', 'qd_exam_materials_qrcode_upload')
                ->first();
            if ($role == 'treasury') {
                $receiveMaterialsPrinterToDistrict = ExamAuditLog::where('exam_id', $examId)
                    ->where('task_type', 'receive_materials_printer_to_disitrct_treasury')
                    ->where('user_id', $user->tre_off_id)
                    ->first();
                $receiveBundleToDistrict = ExamAuditLog::where('exam_id', $examId)
                    ->where('task_type', 'receive_bundle_to_disitrct_treasury')
                    ->where('user_id', $user->tre_off_id)
                    ->first();

            } else {
                $receiveMaterialsPrinterToDistrict = ExamAuditLog::where('exam_id', $examId)
                    ->where('task_type', 'receive_materials_printer_to_disitrct_treasury')
                    ->whereJsonContains('metadata->district_code', $user->district_code)
                    ->first();
                $receiveBundleToDistrict = ExamAuditLog::where('exam_id', $examId)
                    ->where('task_type', 'receive_bundle_to_disitrct_treasury')
                    ->whereJsonContains('metadata->district_code', $user->district_code)
                    ->first();
            }
            if ($role == 'center') {
                $receiveMaterialsDistrictToCenter = ExamAuditLog::where('exam_id', $examId)
                    ->where('task_type', 'receive_materials_disitrct_to_center')
                    ->where('user_id', $user->center_id)
                    ->first();
                $receiveMaterialsMobileteamToCenter = ExamAuditLog::where('exam_id', $examId)
                    ->where('task_type', 'receive_bundle_to_center')
                    ->where('user_id', $user->center_id)
                    ->first();
            }
            if ($role == 'district') {
                $sendExamVenueConsent = ExamAuditLog::where('exam_id', $examId)
                    ->where('task_type', 'exam_venue_consent')
                    ->where('user_id', $user->district_id)
                    ->first();
                $examRoutesCreated = ExamMaterialRoutes::where('exam_id', $examId)
                    ->where('district_code', $user->district_code)
                    ->latest('updated_at') // Fetch the most recently updated record
                    ->first();
            }
            $receiveMaterialsPrinterToHQ = ExamAuditLog::where('exam_id', $examId)
                ->where('task_type', 'receive_materials_printer_to_hq')
                ->first();
            $examTrunkboxOTLData = ExamAuditLog::where('exam_id', $examId)
                ->where('task_type', 'exam_trunkbox_qr_otl_upload')
                ->first();
            $receiveTrunkboxToHQ = ExamAuditLog::where('exam_id', $examId)
                ->where('task_type', 'receive_trunkbox_at_hq')
                ->first();
            $materialsHandoverVerification = ExamAuditLog::where('exam_id', $examId)
                ->where('task_type', 'materials_handover_verification')
                ->first();
            $current_user = $request->get('auth_user');
            return view('my_exam.task', compact('current_user', 'session', 'auditDetails', 'sendExamVenueConsent', 'venueConsents', 'meetingCodeGen', 'expectedCandidatesUpload', 'candidatesCountIncrease', 'examVenueHallConfirmation', 'apdFinalizeHallsUpload', 'examMaterialsUpdate', 'receiveMaterialsPrinterToDistrict', 'receiveMaterialsPrinterToHQ', 'examTrunkboxOTLData', 'examRoutesCreated', 'receiveMaterialsDistrictToCenter', 'receiveMaterialsMobileteamToCenter', 'receiveBundleToDistrict', 'receiveTrunkboxToHQ', 'materialsHandoverVerification'));
        }
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
        $ciMeetingData = DB::table('ci_meeting_attendance')
            ->where('exam_id', $examId)
            ->where('ci_id', $ci_id)
            ->first();
        // dd($ciMeetingData);

        $adequacy_check_data = [];
        $firstReceivedAmount = null;

        if ($ciMeetingData) {
            // Loop through each meeting entry
            // Decode the 'adequacy_check' JSON field
            $adequacyData = json_decode($ciMeetingData->adequacy_check, true);

            // Extract 'received_amount' from the first item, if available
            if (!empty($adequacyData)) {
                $firstReceivedAmount = $adequacyData['received_amount'] ?? null;
            }
        }
        // Group and sort exam sessions by date
        $groupedSessions = $session->examsession
            ->sortBy(function ($item) {
                return Carbon::parse($item->exam_sess_date)->timestamp;
            })
            ->groupBy(function ($item) {
                return Carbon::parse($item->exam_sess_date)->format('d-m-Y');
            });
        // Retrieve preliminary checklist
        $preliminary = CIChecklist::where('ci_checklist_type', 'Preliminary')->get();
        $preliminaryAnswer = CIChecklistAnswer::where('ci_id', $ci_id)
            ->where('exam_id', $examId)
            ->select('preliminary_answer')
            ->first();
        $utilityAnswer = CIChecklistAnswer::where('ci_id', $ci_id)
            ->where('exam_id', $examId)
            ->select('utility_answer')
            ->first();
        // Pass all data to the view
        return view('my_exam.CI.task', compact('session', 'groupedSessions', 'preliminary', 'preliminaryAnswer', 'ciMeetingData', 'firstReceivedAmount', 'utilityAnswer'));
    }

    public function ciExamActivity($examId, $session)
    {
        // Retrieve the session details with related currentexam
        $session = ExamSession::with('currentexam')
            ->where('exam_sess_mainid', $examId)
            ->where('exam_session_id', $session)
            ->first();
        // dd();
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

        // Query based on the role
        $lastScannedMaterial = ExamMaterialsData::where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->whereIn('category', ['D1', 'D2'])
            ->join('exam_materials_scans', function ($join) {
                $join->on('exam_materials_data.id', '=', 'exam_materials_scans.exam_material_id');
            })
            ->orderBy('exam_materials_scans.ci_scanned_at', 'desc') // Get the latest scanned material
            ->select('exam_materials_data.*', 'exam_materials_scans.ci_scanned_at as last_scanned_at')
            ->first(); // Get only one row
        $sessionAnswer = CIChecklistAnswer::where('ci_id', $ci_id)
            ->where('exam_id', $examId)
            ->select(DB::raw("session_answer->'" . $session->exam_sess_date . "'->'" . $session->exam_sess_session . "' as session_answer"))
            ->first();
        $selectedInvigilator = CIStaffAllocation::where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->where('exam_date', $session->exam_sess_date)
            ->select(DB::raw("invigilators->'" . $session->exam_sess_session . "' as selected_invigilators"))
            ->first();
        $selectedScribe = CIStaffAllocation::where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->where('exam_date', $session->exam_sess_date)
            ->select(DB::raw("scribes->'" . $session->exam_sess_session . "' as selected_scribes"))
            ->first();
        $selectedAssistant = CIStaffAllocation::where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->where('exam_date', $session->exam_sess_date)
            ->select(DB::raw("assistants->'" . $session->exam_sess_session . "' as selected_assistants"))
            ->first();
        $qpboxTimeLog = QpBoxLog::where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->where('exam_date', $session->exam_sess_date)
            ->select(DB::raw("qp_timing_log->'" . $session->exam_sess_session . "' as qp_timing_log"))
            ->first();
            // dd($qpboxTimeLog);
        $candidateAttendance = CICandidateLogs::where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->where('exam_date', $session->exam_sess_date)
            ->select(DB::raw("candidate_attendance->'" . $session->exam_sess_session . "' as candidate_attendance"))
            ->first();
        $additionalCandidates = CICandidateLogs::where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->where('exam_date', $session->exam_sess_date)
            ->select(DB::raw("additional_details->'" . $session->exam_sess_session . "' as additional_candidates"))
            ->first();
        $paperReplacements = CIPaperReplacements::where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->where('exam_date', $session->exam_sess_date)
            ->where('exam_session', $session->exam_sess_session)
            ->orderBy('updated_at', 'desc') // Order by latest update
            ->get();
        $candidateRemarks = CICandidateLogs::where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->where('exam_date', $session->exam_sess_date)
            ->select(DB::raw("candidate_remarks->'" . $session->exam_sess_session . "' as candidate_remarks"))
            ->first();
        $videographyAnswer = CIChecklistAnswer::where('ci_id', $ci_id)
            ->where('exam_id', $examId)
            ->select(DB::raw("videography_answer->'" . $session->exam_sess_date . "'->'" . $session->exam_sess_session . "' as videography_answer"))
            ->first();
        $omrRemarks = CICandidateLogs::where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->where('exam_date', $session->exam_sess_date)
            ->select(DB::raw("omr_remarks->'" . $session->exam_sess_session . "' as omr_remarks"))
            ->first();
        // Query based on the role
        $lastScannedBundle = ExamMaterialsData::where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->where('exam_date', $session->exam_sess_date)
            ->where('exam_session', $session->exam_sess_session)
            ->whereIn('category', ['R1', 'R2', 'R3', 'R4', 'R5', 'R6'])
            ->join('exam_materials_scans', function ($join) {
                $join->on('exam_materials_data.id', '=', 'exam_materials_scans.exam_material_id');
            })
            ->orderBy('exam_materials_scans.ci_scanned_at', 'desc') // Get the latest scanned material
            ->select('exam_materials_data.*', 'exam_materials_scans.ci_scanned_at as last_scanned_at')
            ->first(); // Get only one row
        $consolidateAnswer = CIChecklistAnswer::where('ci_id', $ci_id)
            ->where('exam_id', $examId)
            ->select(DB::raw("consolidate_answer->'" . $session->exam_sess_date . "'->'" . $session->exam_sess_session . "' as consolidate_answer"))
            ->first();
        // dd($consolidateAnswer);
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
        // dd($user->ci_venue_id);
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
            'scribe',
            'consolidate_data',
            'ci_id',
            'lastScannedMaterial',
            'sessionAnswer',
            'selectedInvigilator',
            'selectedScribe',
            'selectedAssistant',
            'qpboxTimeLog',
            'candidateAttendance',
            'additionalCandidates',
            'paperReplacements',
            'candidateRemarks',
            'videographyAnswer',
            'omrRemarks',
            'lastScannedBundle',
            'consolidateAnswer'
        ));
    }

    public function mobileTeamTask($examId)
    {
        $session = Currentexam::with('examsession')->where('exam_main_no', $examId)->first();

        if (!$session) {
            abort(404, 'Exam not found');
        }
        // Group exam sessions by date
        $groupedSessions = $session->examsession
            ->groupBy(function ($item) {
                return Carbon::parse($item->exam_sess_date)->format('d-m-Y');
            })
            ->sortBy(function ($sessions, $date) {
                return Carbon::parse($date)->format('d-m-Y');
            });
        $role = session('auth_role');
        $user = current_user();
        // Fetch the audit details for the exam
        $auditDetails = DB::table('exam_audit_logs')
            ->where('exam_id', $examId)
            ->orderBy('created_at', 'asc')
            ->get();
        $receiveMaterialsToMobileteam = null;
        $receiveBundleToMobileteam = null;
        if ($role == 'mobile_team_staffs') {
            $receiveMaterialsToMobileteam = ExamAuditLog::where('exam_id', $examId)
                ->where('task_type', 'receive_materials_to_mobileteam_staff')
                ->where('user_id', $user->mobile_id)
                ->first();
            $receiveBundleToMobileteam = ExamAuditLog::where('exam_id', $examId)
                ->where('task_type', 'receive_bundle_to_mobileteam_staff')
                ->where('user_id', $user->mobile_id)
                ->first();
        }
        if ($role == 'headquarters') {
            $receiveMaterialsToMobileteam = ExamAuditLog::where('exam_id', $examId)
                ->where('task_type', 'receive_materials_to_vanduty_staff')
                ->where('user_id', $user->dept_off_id)
                ->first();
            $receiveBundleToMobileteam = ExamAuditLog::where('exam_id', $examId)
                ->where('task_type', 'receive_bundle_to_vanduty_staff')
                ->where('user_id', $user->dept_off_id)
                ->first();
        }
        return view('my_exam.MobileTeam.task', compact('auditDetails', 'session', 'groupedSessions', 'receiveMaterialsToMobileteam', 'receiveBundleToMobileteam'));
    }
}
