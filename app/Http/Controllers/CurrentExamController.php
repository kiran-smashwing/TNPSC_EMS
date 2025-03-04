<?php

namespace App\Http\Controllers;

use App\Models\Currentexam;
use App\Models\ExamCandidatesProjection;
use App\Models\ExamConfirmedHalls;
use App\Models\ExamMaterialRoutes;
use App\Models\ExamSession;
use App\Models\ExamService;
use App\Models\ExamVenueConsent;
use App\Services\AuditLogger;
use App\Services\ExamAuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class CurrentExamController extends Controller
{
    protected $auditService;

    public function __construct(ExamAuditService $auditService)
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
        $this->auditService = $auditService;
    }

    //  public function index()
    //{
    // Get today's date in 'DD-MM-YYYY' format (to match database format)
    //  $today = Carbon::today()->format('d-m-Y');

    // Fetch only the current exams that have not yet ended (last date >= today)
    //$exams = Currentexam::withCount('examsession')
    //  ->whereRaw("TO_DATE(exam_main_lastdate, 'DD-MM-YYYY') >= TO_DATE(?, 'DD-MM-YYYY')", [$today])
    //->orderBy('exam_main_createdat', 'desc')
    //->get();
    //return view('current_exam.index', compact('exams'));
    //}

    public function index()
    {
        $user = current_user();
        $role = session('auth_role');
        $examIds = null; // Initialize as null so we know if a filter should be applied
    
        switch ($role) {
            case 'district':
            case 'treasury':
                $examIds = ExamCandidatesProjection::where('district_code', $user->district_code)
                    ->pluck('exam_id')
                    ->unique()
                    ->values();
                break;
            case 'center':
                $examIds = ExamCandidatesProjection::where('center_code', $user->center_code)
                    ->pluck('exam_id')
                    ->unique()
                    ->values();
                break;
            case 'venue':
                $examIds = ExamVenueConsent::where('venue_id', $user->venue_id)
                    ->pluck('exam_id')
                    ->unique()
                    ->values();
                break;
            case 'mobile_team_staffs':
                $examIds = ExamMaterialRoutes::where('mobile_team_staff', $user->mobile_id)
                    ->pluck('exam_id')
                    ->unique()
                    ->values();
                break;
            case 'ci':
                $examIds = ExamConfirmedHalls::where('ci_id', $user->ci_id)
                    ->where('is_apd_uploaded', true)
                    ->where('alloted_count', '>', 0)
                    ->pluck('exam_id')
                    ->unique()
                    ->values();
                break;
        }
    
        $examQuery = Currentexam::withCount('examsession')
            ->orderBy('exam_main_createdat', 'desc');
    
        // If a role-specific exam ID list is set, filter by it
        if (!is_null($examIds)) {
            $examQuery->whereIn('exam_main_no', $examIds);
        }
    
        $exams = $examQuery->get();
    
        return view('current_exam.index', compact('exams'));
    }
    

    public function create()
    {
        $examServices = ExamService::all();
        return view('current_exam.create', compact('examServices'));
    }
    public function store(Request $request)
    {
        // Custom error messages for validation
        $messages = [
            'exam_main_no.required' => 'The exam number is required.',
            'exam_main_no.unique' => 'The exam number must be unique.',
            'exam_main_name.required' => 'The exam name is required.',
            'exam_main_startdate.date' => 'The start date must be a valid date.',
            'exam_main_lastdate.date' => 'The last date must be a valid date.',
            'exam_main_candidates_for_hall.required' => 'The count of candidates for each hall is required.',
        ];

        // Validate the incoming request
        $validated = $request->validate([
            'exam_main_no' => 'required|string|max:255|unique:exam_main,exam_main_no',
            'exam_main_type' => 'nullable|string|max:255',
            'exam_main_model' => 'nullable|string|max:255',
            'exam_main_tiers' => 'nullable|string|max:255',
            'exam_main_service' => 'nullable|string|max:255',
            'exam_main_notification' => 'required|string|max:255|unique:exam_main,exam_main_notification,NULL,NULL,exam_main_tiers,' . $request->input('exam_main_tiers'),
            'exam_main_notifdate' => 'nullable|date',
            'exam_main_name' => 'required|string|max:255',
            'exam_main_nametamil' => 'nullable|string|max:255',
            // 'exam_main_postname' => 'nullable|string|max:255',
            'exam_main_lastdate' => 'nullable|date',
            'exam_main_startdate' => 'nullable|date',
            'exam_main_flag' => 'nullable|string|max:10',
            'subjects' => 'required|array',
            'subjects.*.date' => 'required|date',
            'subjects.*.session' => 'required|string',
            'subjects.*.time' => 'required|string',
            'subjects.*.duration' => 'required|string',
            'subjects.*.name' => 'required|string',
            'subjects.*.type' => 'nullable|string',
            'exam_main_candidates_for_hall' => 'required|integer',
        ], $messages);


        try {
            DB::beginTransaction();

            // Create a new `Currentexam` record
            $examMain = Currentexam::create([
                'exam_main_no' => $validated['exam_main_no'],
                'exam_main_type' => $validated['exam_main_type'] ?? null,
                'exam_main_model' => $validated['exam_main_model'] ?? null,
                'exam_main_tiers' => $validated['exam_main_tiers'] ?? null,
                'exam_main_service' => $validated['exam_main_service'] ?? null,
                'exam_main_notification' => $validated['exam_main_notification'] ?? null,
                'exam_main_notifdate' => $validated['exam_main_notifdate'] ?? null,
                'exam_main_name' => $validated['exam_main_name'],
                'exam_main_nametamil' => $validated['exam_main_nametamil'] ?? null,
                // 'exam_main_postname' => $validated['exam_main_postname'] ?? null,
                'exam_main_lastdate' => $validated['exam_main_lastdate'] ?? null,
                'exam_main_startdate' => $validated['exam_main_startdate'] ?? null,
                'exam_main_flag' => $validated['exam_main_flag'] ?? null,
                'exam_main_candidates_for_hall' => $validated['exam_main_candidates_for_hall'],
            ]);

            // Save the subjects in the `exam_session` table
            foreach ($validated['subjects'] as $subject) {
                ExamSession::create([
                    'exam_sess_mainid' => $validated['exam_main_no'],
                    'exam_sess_date' => $subject['date'],
                    'exam_sess_session' => $subject['session'],
                    'exam_sess_time' => $subject['time'],
                    'exam_sess_duration' => $subject['duration'],
                    'exam_sess_subject' => $subject['name'],
                    'exam_sess_type' => $subject['type'],
                    'exam_sess_flag' => 'active', // Set a default value for the flag
                ]);
            }

            DB::commit();
            // Log the action
            $currentUser = current_user();
            $userName = $currentUser ? $currentUser->display_name : 'Unknown';
            $this->auditService->log(
                examId: $examMain->exam_main_no,
                actionType: 'created',
                taskType: 'exam_metadata',
                afterState: $examMain->toArray(),
                description: 'Created exam metadata',
                metadata: ['user_name' => $userName]
            );


            // Log the creation of the exam (optional, replace `AuditLogger` with your own logger if needed)
            // AuditLogger::log('Exam Created', Currentexam::class, $examMain->exam_main_id, null, $examMain->toArray());

            // Redirect to the index route with a success message
            return redirect()->route('current-exam.index')->with('success', 'Exam created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Return with an error message on exception
            return back()->withInput()
                ->with('error', 'Error creating exam: ' . $e->getMessage());
        }
    }
    public function edit($id)
    {
        $exam = Currentexam::with('examsession')->findOrFail($id); // Fetch the exam with the given ID and its related exam sessions
        $examServices = ExamService::all(); // Retrieve all exam services
        return view('current_exam.edit', compact('exam', 'examServices')); // Pass the exam to the edit view
    }

    public function update(Request $request, $id)
    {
        $message = [
            'exam_main_no.required' => 'The exam number is required.',
            'exam_main_no.unique' => 'The exam number must be unique.',
            'exam_main_name.required' => 'The exam name is required.',
            'exam_main_startdate.date' => 'The start date must be a valid date.',
            'exam_main_lastdate.date' => 'The last date must be a valid date.',
            'exam_main_candidates_for_hall.required' => 'The count of candidates for each hall is required.',
        ];
        $validated = $request->validate([
            'exam_main_no' => 'required|string|max:255|unique:exam_main,exam_main_no,' . $id . ',exam_main_id',
            'exam_main_type' => 'nullable|string|max:255',
            'exam_main_model' => 'nullable|string|max:255',
            'exam_main_tiers' => 'nullable|string|max:255',
            'exam_main_service' => 'nullable|string|max:255',
            'exam_main_notification' => 'required|string|max:255|unique:exam_main,exam_main_notification,' . $id . ',exam_main_id,exam_main_tiers,' . $request->input('exam_main_tiers'),
            'exam_main_notifdate' => 'nullable|date',
            'exam_main_name' => 'required|string|max:255',
            'exam_main_nametamil' => 'nullable|string|max:255',
            // 'exam_main_postname' => 'nullable|string|max:255',
            'exam_main_lastdate' => 'nullable|date',
            'exam_main_startdate' => 'nullable|date',
            'exam_main_flag' => 'nullable|string|max:10',
            'subjects' => 'required|array',
            'subjects.*.date' => 'required|date',
            'subjects.*.session' => 'required|string',
            'subjects.*.time' => 'required|string',
            'subjects.*.duration' => 'required|string',
            'subjects.*.name' => 'required|string',
            'subjects.*.type' => 'required|string',
            'exam_main_candidates_for_hall' => 'required|integer',
        ], $message);

        try {
            DB::beginTransaction();

            // Update the `Currentexam` record
            $exam = Currentexam::findOrFail($id);
            $exam->update([
                'exam_main_no' => $validated['exam_main_no'],
                'exam_main_type' => $validated['exam_main_type'] ?? null,
                'exam_main_model' => $validated['exam_main_model'] ?? null,
                'exam_main_tiers' => $validated['exam_main_tiers'] ?? null,
                'exam_main_service' => $validated['exam_main_service'] ?? null,
                'exam_main_notification' => $validated['exam_main_notification'] ?? null,
                'exam_main_notifdate' => $validated['exam_main_notifdate'] ?? null,
                'exam_main_name' => $validated['exam_main_name'],
                'exam_main_nametamil' => $validated['exam_main_nametamil'] ?? null,
                // 'exam_main_postname' => $validated['exam_main_postname'] ?? null,
                'exam_main_lastdate' => $validated['exam_main_lastdate'] ?? null,
                'exam_main_startdate' => $validated['exam_main_startdate'] ?? null,
                'exam_main_flag' => $validated['exam_main_flag'] ?? null,
                'exam_main_candidates_for_hall' => $validated['exam_main_candidates_for_hall'],
            ]);

            // Delete the existing subjects
            ExamSession::where('exam_sess_mainid', $exam->exam_main_no)->delete();

            // Save the updated subjects in the `exam_session` table
            foreach ($validated['subjects'] as $subject) {
                ExamSession::create([
                    'exam_sess_mainid' => $validated['exam_main_no'],
                    'exam_sess_date' => $subject['date'],
                    'exam_sess_session' => $subject['session'],
                    'exam_sess_time' => $subject['time'],
                    'exam_sess_duration' => $subject['duration'],
                    'exam_sess_subject' => $subject['name'],
                    'exam_sess_type' => $subject['type'],
                    'exam_sess_flag' => 'active', // Set a default value for the flag
                ]);
            }

            DB::commit();

            // Log the update of the exam (optional, replace `AuditLogger` with your own logger if needed)
            AuditLogger::log('Exam Updated', Currentexam::class, $exam->exam_main_id, $exam->toArray(), $exam->getChanges());

            // Redirect to the index route with a success message
            return redirect()->route('current-exam.index')->with('success', 'Exam updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Return with an error message on exception
            return back()->withInput()
                ->with('error', 'Error updating exam: ' . $e->getMessage());
        }
    }
    public function getExamByNotificationNo(Request $request)
    {

        $notificationNumber = $request->input('notificationNumber');
        // Fetch the exam details using the notification number
        $exam = Currentexam::with('examsession')
            ->where('exam_main_notification', $notificationNumber)
            ->where('exam_main_tiers', '2-Preliminary')
            ->first();

        // Check if the exam exists
        if (!$exam) {
            return response()->json(['error' => 'Exam not found'], 404);
        }

        // Return the exam details as a JSON response
        return response()->json($exam);
    }

    public function show($id)
    {
        $exam = Currentexam::with(relations: ['examsession', 'examservice'])->findOrFail($id); // Fetch the exam with the given ID and its related exam sessions
        return view('current_exam.show', compact('exam')); // Pass the exam to
    }

    public function task()
    {
        return view('current_exam.task');
    }
    public function ciTask()
    {
        return view('current_exam.ci-task');
    }
    public function ciMeeting()
    {
        return view('current_exam.ci-meeting');
    }
    public function routeView()
    {
        return view('current_exam.route.route-view');
    }
    public function routeCreate()
    {
        return view('current_exam.route.route-create');
    }
    public function routeEdit()
    {
        return view('current_exam.route.route-edit');
    }
    public function updateMaterialScanDetails()
    {
        return view('current_exam.material-scan-details');
    }
    public function districtCollectrateTask()
    {
        return view('current_exam.district-task');
    }
    public function examActivityTask()
    {
        return view('current_exam.exam-activities-task');
    }

    public function increaseCandidate()
    {
        return view('current_exam.increase-candidate');
    }
    public function venueConsent()
    {
        return view('current_exam.venue-consent');
    }



    public function confirmVenues()
    {
        return view('current_exam.confirm-venues');
    }
    public function ciReceiveMaterials()
    {
        return view('current_exam.ci-receive-materials');
    }
    public function mobileTeamReceiveMaterialsFromTreasury()
    {
        return view('current_exam.treasury-to-mobileTeam-materials');
    }
    public function ciReceiveMaterialsFromMobileTeam()
    {
        return view('current_exam.mobileTeam-to-CI-materials');
    }
    public function bundlePackaging()
    {
        return view('current_exam.bundle-packaging');
    }
    public function bundlePackagingverfiy()
    {
        return view('current_exam.bundle-CI-to-mobile-team');
    }
    public function vandutyBundlePackagingverfiy()
    {
        return view('current_exam.bundle-CI-to-van-duty');
    }
    public function vdstotreasuryofficer()
    {
        return view('current_exam.vds-to-treasury-officer');
    }
}
