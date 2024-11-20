<?php

namespace App\Http\Controllers;

use App\Models\Currentexam;
use App\Services\AuditLogger;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class CurrentExamController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
    }

    public function index()
    {
        // Fetch all exams from the `exam_main` table
        $exams = Currentexam::orderBy('exam_main_createdat', 'desc')->get();

        // Pass the exams to the index view
        return view('current_exam.index', compact('exams'));
    }

    public function create()
    {
        return view('current_exam.create');
    }
    public function store(Request $request)
    {
        // dd($request->all());
        // Custom error messages for validation
        $messages = [
            'exam_main_no.required' => 'The exam number is required.',
            'exam_main_no.unique' => 'The exam number must be unique.',
            'exam_main_name.required' => 'The exam name is required.',
            'exam_main_startdate.date' => 'The start date must be a valid date.',
            'exam_main_lastdate.date' => 'The last date must be a valid date.',
        ];

        // Validate the incoming request
        $validated = $request->validate([
            // 'exam_main_no' => 'required|string|max:255|unique:exam_main,exam_main_no',
            'exam_main_type' => 'nullable|string|max:255',
            'exam_main_model' => 'nullable|string|max:255',
            'exam_main_tiers' => 'nullable|string|max:255',
            'exam_main_service' => 'nullable|string|max:255',
            'exam_main_notification' => 'nullable|string|max:255',
            'exam_main_notifdate' => 'nullable|date',
            'exam_main_name' => 'required|string|max:255',
            'exam_main_nametamil' => 'nullable|string|max:255',
            'exam_main_postname' => 'nullable|string|max:255',
            'exam_main_lastdate' => 'nullable|date',
            'exam_main_startdate' => 'nullable|date',
            'exam_main_flag' => 'nullable|string|max:10',
        ], $messages);
        // dd($validated);

        try {
            // Create a new `ExamMain` record
            $examMain = Currentexam::create([
                // 'exam_main_no' => $validated['exam_main_no'],
                'exam_main_type' => $validated['exam_main_type'] ?? null,
                'exam_main_model' => $validated['exam_main_model'] ?? null,
                'exam_main_tiers' => $validated['exam_main_tiers'] ?? null,
                'exam_main_service' => $validated['exam_main_service'] ?? null,
                'exam_main_notification' => $validated['exam_main_notification'] ?? null,
                'exam_main_notifdate' => $validated['exam_main_notifdate'] ?? null,
                'exam_main_name' => $validated['exam_main_name'],
                'exam_main_nametamil' => $validated['exam_main_nametamil'] ?? null,
                'exam_main_postname' => $validated['exam_main_postname'] ?? null,
                'exam_main_lastdate' => $validated['exam_main_lastdate'] ?? null,
                'exam_main_startdate' => $validated['exam_main_startdate'] ?? null,
                'exam_main_flag' => $validated['exam_main_flag'] ?? null,
            ]);

            // Log the creation of the exam (optional, replace `AuditLogger` with your own logger if needed)
            AuditLogger::log('Exam Created', Currentexam::class, $examMain->exam_main_id, null, $examMain->toArray());

            // Redirect to the index route with a success message
            return redirect()->route('current-exam')->with('success', 'Exam created successfully.');
        } catch (\Exception $e) {
            // Return with an error message on exception
            return back()->withInput()
                ->with('error', 'Error creating exam: ' . $e->getMessage());
        }
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
    public function edit($id)
    {
        $exam = Currentexam::findOrFail($id); // Fetch the exam or throw a 404 error
        return view('current_exam.edit', compact('exam')); // Pass the exam to the edit view
    }

    public function update(Request $request, $id)
    {
        // Custom error messages for validation
        $messages = [
            'exam_main_no.required' => 'The exam number is required.',
            'exam_main_no.unique' => 'The exam number must be unique.',
            'exam_main_name.required' => 'The exam name is required.',
        ];

        // Validate the incoming request
        $validated = $request->validate([
            // 'exam_main_no' => 'required|string|max:255|unique:exam_main,exam_main_no,' . $id . ',exam_main_id',
            'exam_main_type' => 'nullable|string|max:255',
            'exam_main_model' => 'nullable|string|max:255',
            'exam_main_tiers' => 'nullable|string|max:255',
            'exam_main_service' => 'nullable|string|max:255',
            'exam_main_notification' => 'nullable|string|max:255',
            'exam_main_notifdate' => 'nullable|date',
            'exam_main_name' => 'required|string|max:255',
            'exam_main_nametamil' => 'nullable|string|max:255',
            'exam_main_postname' => 'nullable|string|max:255',
            'exam_main_lastdate' => 'nullable|date',
            'exam_main_startdate' => 'nullable|date',
            'exam_main_flag' => 'nullable|string|max:10',
        ], $messages);

        try {
            // Fetch the record to update
            $exam = Currentexam::findOrFail($id);

            // Update the record
            $exam->update($validated);

            // Log the update (optional)
            AuditLogger::log('Exam Updated', Currentexam::class, $id, $exam->getOriginal(), $exam->toArray());

            // Redirect with success message
            return redirect()->route('current-exam')->with('success', 'Exam updated successfully.');
        } catch (\Exception $e) {
            // Return with an error message on exception
            return back()->withInput()
                ->with('error', 'Error updating exam: ' . $e->getMessage());
        }
    }


    public function increaseCandidate()
    {
        return view('current_exam.increase-candidate');
    }
    public function venueConsent()
    {
        return view('current_exam.venue-consent');
    }
    public function sendMailtoCollectorate()
    {
        return view('current_exam.send-mailto-collectorate');
    }

    public function selectSendMailtoVenue()
    {
        return view('current_exam.send-mailto-venue');
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
