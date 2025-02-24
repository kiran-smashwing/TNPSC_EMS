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
        $validated = $request->validate([
            'exam_id' => 'required',
            'exam_sess_date' => 'required|date',
            'exam_sess_session' => 'required|string',
            'checklist' => 'nullable|array',
            'remarks' => 'nullable|array',
        ]);

        $user = current_user();

        // Get exam details from confirmed halls
        $examDetails = DB::table('exam_confirmed_halls')
            ->where('exam_id', $validated['exam_id'])
            ->where('ci_id', $user->ci_id)
            ->first();

        if (!$examDetails) {
            return back()->withErrors(['exam_id' => 'Exam not found in confirmed halls.']);
        }

        // Find or create record
        $ciVideographyChecklist = CIChecklistAnswer::firstOrCreate([
            'exam_id' => $validated['exam_id'],
            'center_code' => $examDetails->center_code,
            'hall_code' => $examDetails->hall_code,
            'ci_id' => $user->ci_id,
        ], [
            'videography_answer' => []
        ]);

        $currentData = $ciVideographyChecklist->videography_answer ?? [];
        $exam_date = $validated['exam_sess_date'];
        $session = $validated['exam_sess_session'];

        // Prepare checklist data
        if (!empty($validated['checklist'])) {
            $checklistData = [];
            foreach ($validated['checklist'] as $checklistId => $value) {
                $checklistData[$checklistId] = [
                    'value' => $value,
                    'remark' => $validated['remarks'][$checklistId] ?? null
                ];
            }

            // Handle nested data structure
            if (!isset($currentData[$exam_date])) {
                $currentData[$exam_date] = [];
            }

            // Update session data while preserving other sessions
            $currentData[$exam_date][$session] = [
                'checklist' => $checklistData,
                'timestamp' => now()->toDateTimeString()
            ];

            // Save the updated data
            $ciVideographyChecklist->videography_answer = $currentData;
            $ciVideographyChecklist->save();
        }

        return redirect()->back()->with('success', 'Videography checklist updated successfully.');
    }

    public function saveConsolidateCertificate(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required',
            'exam_sess_date' => 'required|date',
            'exam_sess_session' => 'required|string',
            'checklists' => 'nullable|array',
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
        $consolidateRecord = CIChecklistAnswer::firstOrCreate([
            'exam_id' => $validated['exam_id'],
            'ci_id' => $user->ci_id,
            'center_code' => $examDetails->center_code,
            'hall_code' => $examDetails->hall_code,

        ], [
            'consolidate_answer' => [],
            'created_at' => now()
        ]);

        $examDate = $validated['exam_sess_date'];
        $session = $validated['exam_sess_session'];
        $currentData = $consolidateRecord->consolidate_answer ?: [];

        // Get existing session data or initialize new
        $dateData = $currentData[$examDate] ?? [];
        $sessionData = $dateData[$session] ?? [
            'checklist' => [],
            'timestamp' => now()->toDateTimeString()
        ];

        // Convert checklists array to simple key-value pairs
        $checklistData = [];
        if (!empty($validated['checklists'])) {
            foreach ($validated['checklists'] as $checklistId => $status) {
                $checklistData[$checklistId] = $status;
            }
        }

        // Update session data
        $sessionData = [
            'checklist' => $checklistData,
            'timestamp' => now()->toDateTimeString()
        ];

        // Update nested structure
        if (!isset($currentData[$examDate])) {
            $currentData[$examDate] = [];
        }
        $currentData[$examDate][$session] = $sessionData;

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
