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
        $user = current_user();
        // Retrieve the exam details
        $examDetails = DB::table('exam_confirmed_halls')
            ->where('exam_id', $validated['exam_id'])
            ->where('ci_id', $user->ci_id)
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
        // Update or create the checklist answer
        CIChecklistAnswer::updateOrCreate(
            [
                'exam_id' => $validated['exam_id'],
                'center_code' => $examDetails->center_code,
                'hall_code' => $examDetails->hall_code,
                'ci_id' => $ci_id,
            ],
            [
                'preliminary_answer' => $preliminaryAnswer, // Save as JSON with the single timestamp
            ]
        );

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Checklist saved successfully!');
    }
    public function saveSessionChecklist(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'exam_id' => 'required|numeric',
            'exam_sess_date' => 'required|date',
            'exam_sess_session' => 'required|string',
            'checklist' => 'nullable|array',
            'inspectionStaff' => 'nullable|array',
        ]);

        $user = current_user();

        // Retrieve the exam details from exam_confirmed_halls
        $examDetails = DB::table('exam_confirmed_halls')
            ->where('exam_id', $validated['exam_id'])
            ->where('ci_id', $user->ci_id)
            ->first();

        if (!$examDetails) {
            return back()->withErrors(['exam_id' => 'Exam not found in confirmed halls.']);
        }

        // Retrieve the existing checklist record or create a new one
        $ciSessionChecklist = CIChecklistAnswer::firstOrCreate(
            [
                'exam_id' => $validated['exam_id'],
                'center_code' => $examDetails->center_code,
                'hall_code' => $examDetails->hall_code,
                'ci_id' => $user->ci_id,
            ],
            [
                'session_answer' => []
            ]
        );

        $currentData = $ciSessionChecklist->session_answer ?? [];
        $exam_date = $validated['exam_sess_date'];
        $session = $validated['exam_sess_session'];

        if (!empty($validated['checklist'])) {
            $checklistData = [];
            $inspectionStaffData = [];

            foreach ($validated['checklist'] as $checklistId => $value) {
                $checklistData[$checklistId] = $value;

                if (isset($validated['inspectionStaff'][$checklistId])) {
                    $inspectionStaffData[$checklistId] = [
                        'name' => $validated['inspectionStaff'][$checklistId]['name'] ?? null,
                        'designation' => $validated['inspectionStaff'][$checklistId]['designation'] ?? null,
                        'department' => $validated['inspectionStaff'][$checklistId]['department'] ?? null,
                    ];
                }
            }

            // Handle nested data structure updates
            if (!isset($currentData[$exam_date])) {
                // If date doesn't exist, create new date entry
                $currentData[$exam_date] = [];
            }

            // Update or create session data while preserving other sessions
            $currentData[$exam_date][$session] = [
                'checklist' => $checklistData,
                'timestamp' => now()->toDateTimeString(),
                'inspection_staff' => $inspectionStaffData
            ];

            // Save the updated data
            $ciSessionChecklist->session_answer = $currentData;
            $ciSessionChecklist->save();
        }

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
        $user = current_user();
        // Find the existing utilization record by exam_id
        $utilizationRecord = CIChecklistAnswer::where('exam_id', $request->exam_id)->where('ci_id', $user->ci_id)->first();

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
            'updated_at' => $timestamp, // Add the update timestamp
        ];

        // Update the existing record's jsonb field
        $utilizationRecord->utility_answer = $data;
        $utilizationRecord->save();

        return redirect()->back()->with('success', 'Utilization Certificate details updated successfully!');
    }
}
