<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CIChecklistAnswer; // Import the model
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CIPreliminaryCheckController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.multi');
    }

    public function saveChecklist(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'checklist' => 'required|array',
            'exam_id' => 'required|numeric',
        ]);

        // Retrieve the exam details
        $examDetails = DB::table('exam_confirmed_halls')
            ->where('exam_id', $validated['exam_id'])
            ->first();

        if (!$examDetails) {
            return back()->withErrors(['exam_id' => 'Exam not found in confirmed halls.']);
        }

        // Retrieve the authenticated user
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        if (!$user || !isset($user->ci_id)) {
            return back()->withErrors(['auth' => 'Unable to retrieve the authenticated user.']);
        }

        $ci_id = $user->ci_id;

        // Prepare data for saving
        $timestamp = now()->toDateTimeString(); // Single timestamp for all items


        // Create the preliminary answer array with just checklist IDs (no timestamp for each)
        $preliminaryAnswer = [
            'checklist' => $validated['checklist'], // Store all checklist IDs in an array
            'timestamp' => $timestamp, // Attach a single timestamp at the end
        ];

        // Save the data in JSON format
        CIChecklistAnswer::create([
            'exam_id' => $validated['exam_id'],
            'center_code' => $examDetails->center_code,
            'hall_code' => $examDetails->hall_code,
            'ci_id' => $ci_id,
            'preliminary_answer' => $preliminaryAnswer, // Save as JSON with the single timestamp
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Checklist saved successfully!');
    }
    public function savesessionChecklist(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'exam_id' => 'required',
            'exam_sess_date' => 'required|date',
            'exam_sess_session' => 'required|string',
            'checklist' => 'nullable|array',
            'inspectionStaff' => 'nullable|array',
        ]);
        
        // Find the existing record or create a new one
        $ciSessionChecklist = CIChecklistAnswer::firstOrNew(['exam_id' => $request->exam_id]);

        // Retrieve the current data or initialize an empty structure
        $currentData = $ciSessionChecklist->session_answer ?? [
            'sessions' => []
        ];
       
        // Prepare the new session data
        $exam_date = $request->input('exam_sess_date');
        $sessions = $request->input('exam_sess_session');
        $checklistData = [];

        if (!empty($request->checklist)) {
            foreach ($request->checklist as $checklistId => $value) {
                // Create checklist item array
                $checklistItem = [
                    'description' => $value,
                    'checklist_id' => $checklistId,
                ];

                // Add dynamic fields for Inspection Staff (if provided)
                if (isset($request->inspectionStaff[$checklistId])) {
                    $checklistItem['inspection_staff'] = [
                        'name' => $request->input("inspectionStaff.{$checklistId}.name"),
                        'designation' => $request->input("inspectionStaff.{$checklistId}.designation"),
                        'department' => $request->input("inspectionStaff.{$checklistId}.department")
                    ];
                }

              

                // Add the checklist item to the data array
                $checklistData[] = $checklistItem;
            }

            // Append the new session data with current timestamp
            $currentData['sessions'][] = [
                'exam_date' => $exam_date,
                'session' => $sessions,
                'checklist' => $checklistData,
                'timestamp' => now()->toDateTimeString(),
            ];
        }

        // Save the updated session_answer data in the ciSessionChecklist record
        $ciSessionChecklist->session_answer = $currentData;

        // Save the record (this will insert if it's a new record or update if it exists)
        $ciSessionChecklist->save();

        // Return success message
        return redirect()->back()->with('success', 'Checklist updated successfully.');
    }




    public function saveVideographyChecklist(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'exam_id' => 'required',
            'exam_sess_date' => 'required|date',
            'exam_sess_session' => 'required|string',
            'checklist' => 'nullable|array',
            'inspectionStaff' => 'nullable|array',
        ]);
        // dd($validated);
        // Find the existing record in the ci_checklist_answer table
        $ciVideographyChecklist = CIChecklistAnswer::where('exam_id', $request->exam_id)->first();

        if (!$ciVideographyChecklist) {
            return redirect()->back()->with('error', 'Record not found.');
        }

        // Retrieve the current data or initialize an empty structure
        $currentData = $ciVideographyChecklist->videography_answer ?? [
            'sessions' => []
        ];

        // Prepare the new session data
        $exam_date = $request->input('exam_sess_date');
        $sessions = $request->input('exam_sess_session');
        $checklistData = [];

        if (!empty($request->checklist)) {
            foreach ($request->checklist as $checklistId => $value) {
                $checklistItem = [
                    'description' => $value,
                    'checklist_id' => $checklistId,
                ];

                // Add dynamic fields for Inspection Staff
                if ($request->has("inspectionStaff.{$checklistId}")) {
                    $checklistItem['inspection_staff'] = $request->input("inspectionStaff.{$checklistId}");
                }

                $checklistData[] = $checklistItem;
            }

            // Append the new session data
            $currentData['sessions'][] = [
                'exam_date' => $exam_date,
                'session' => $sessions,
                'checklist' => $checklistData,
                'timestamp' => now()->toDateTimeString(), // Add current timestamp
            ];
        }

        // Save the updated array data in the videography_answer column
        $ciVideographyChecklist->videography_answer = $currentData;
        $ciVideographyChecklist->save();

        return redirect()->back()->with('success', 'Videography checklist updated successfully.');
    }

    public function saveConsolidateCertificate(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'exam_id' => 'required',
            'exam_sess_date' => 'required|date',
            'exam_sess_session' => 'required|string',
            'checklists' => 'nullable|array',
        ]);

        // Find the existing record in the consolidate_answer table
        $consolidateRecord = CIChecklistAnswer::where('exam_id', $request->exam_id)->first();

        if (!$consolidateRecord) {
            return redirect()->back()->with('error', 'Record not found.');
        }

        // Retrieve the current data or initialize an empty structure
        $currentData = $consolidateRecord->consolidate_answer ?? [
            'sessions' => []
        ];

        // Prepare the new session data
        $examDate = $request->input('exam_sess_date');
        $session = $request->input('exam_sess_session');
        $checklistData = [];

        if (!empty($request->checklists)) {
            foreach ($request->checklists as $checklistId => $status) {
                $checklistData[] = [
                    'description' => $checklistId,
                    'status' => $status, // Save the status as 0 or 1
                ];
            }

            // Append the new session data
            $currentData['sessions'][] = [
                'exam_date' => $examDate,
                'session' => $session,
                'checklist' => $checklistData,
                'timestamp' => now()->toDateTimeString(), // Add current timestamp
            ];
        }

        // Save the updated array data in the consolidate_answer column
        $consolidateRecord->consolidate_answer = $currentData;
        $consolidateRecord->save();

        return redirect()->back()->with('success', 'Consolidate Certificate details saved successfully!');
    }

    public function saveUtilizationCertificate(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'exam_id' => 'required',
            'ciAmount' => 'required|numeric',
            'assistantStaffAmount' => 'required|numeric',
            'policeAmount' => 'required|numeric',
            'scribeAmount' => 'nullable|numeric',
            'inspectionStaffAmount' => 'required|numeric',
            'stationeryAmount' => 'required|numeric',
            'hallRentAmount' => 'required|numeric',
            'totalAmountSpent' => 'required|numeric',
            'amountReceived' => 'required|numeric',
            'balanceAmount' => 'required|numeric',
        ]);

        // Find the existing utilization record by exam_id
        $utilizationRecord = CIChecklistAnswer::where('exam_id', $request->exam_id)->first();

        if (!$utilizationRecord) {
            return redirect()->back()->with('error', 'Record not found.');
        }

        // Retrieve the authenticated user
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        if (!$user || !isset($user->ci_id)) {
            return back()->withErrors(['auth' => 'Unable to retrieve the authenticated user.']);
        }
        $timestamp = now()->toDateTimeString(); // Single timestamp for all items
        // Prepare the data to be stored in the jsonb column
        $data = [
            // 'exam_id' => $validated['exam_id'],
            'ciAmount' => $validated['ciAmount'],
            'assistantStaffAmount' => $validated['assistantStaffAmount'],
            'policeAmount' => $validated['policeAmount'],
            'scribeAmount' => $validated['scribeAmount'] ?? null,
            'inspectionStaffAmount' => $validated['inspectionStaffAmount'],
            'stationeryAmount' => $validated['stationeryAmount'],
            'hallRentAmount' => $validated['hallRentAmount'],
            'totalAmountSpent' => $validated['totalAmountSpent'],
            'amountReceived' => $validated['amountReceived'],
            'balanceAmount' => $validated['balanceAmount'],
            'updated_at' =>  $timestamp, // Add the update timestamp
        ];

        // Update the existing record's jsonb field
        $utilizationRecord->utility_answer = $data;
        $utilizationRecord->save();

        return redirect()->back()->with('success', 'Utilization Certificate details updated successfully!');
    }
}
