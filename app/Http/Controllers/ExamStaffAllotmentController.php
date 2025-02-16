<?php

namespace App\Http\Controllers;

use App\Models\CIStaffAllocation;
use App\Models\ExamSession;
use App\Models\Invigilator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ExamStaffAllotmentController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
    }
    public function saveInvigilatorDetails(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'exam_id' => 'required',
            'exam_sess_date' => 'required|date',
            'exam_sess_session' => 'required|string',
            'invigilators' => 'nullable|array',
        ]);

        // Get authenticated user
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        if (!$user || !isset($user->ci_id)) {
            return back()->withErrors(['auth' => 'Unable to retrieve the authenticated user.']);
        }
        // Prepare the session data
        $exam_date = $validated['exam_sess_date'];
        $session = $validated['exam_sess_session'];

        $CIStaffAllocation = CIStaffAllocation::firstOrCreate([
            'exam_id' => $validated['exam_id'],
            'exam_date' => $exam_date,
            'ci_id' => $user->ci_id,
        ], [
            'invigilators' => [],
            'created_at' => now()
        ]);

        $currentData = $CIStaffAllocation->invigilators ?: [];

        // Simple session data with just invigilator IDs and timestamp
        $sessionData = [
            'invigilators' => $validated['invigilators'] ?? [],
            'timestamp' => now()->toDateTimeString()
        ];

        // Update or add new session while preserving other sessions
        $currentData[$session] = $sessionData;

        $CIStaffAllocation->invigilators = $currentData;
        $CIStaffAllocation->save();

        return redirect()->back()->with('success', 'Invigilators Added successfully.');
    }
    public function allocateHallsRandomly(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required',
            'exam_sess_date' => 'required|date',
            'exam_sess_session' => 'required|string',
            'exam_session_id' => 'nullable',
        ]);

        $exam_date = $validated['exam_sess_date'];
        $session = $validated['exam_sess_session'];
        $user = current_user();
        $currentDateTime = now(); // Current DateTime
// Convert exam date from dd-mm-yyyy PostgreSQL format to Y-m-d format
        $examDateFormatted = Carbon::createFromFormat('d-m-Y', $validated['exam_sess_date'])->format('Y-m-d');
        $examsession = ExamSession::where('exam_session_id', $validated['exam_session_id'])->first();
        // Create exam datetime using 24-hour format
        $examDateTime = Carbon::createFromFormat('Y-m-d H:i A', "$examDateFormatted {$examsession->exam_sess_time}");
        // If exam is today, check if within 30 minutes of start time
        if ($examDateTime->isToday()) {
            $minutesDiff = $currentDateTime->diffInMinutes($examDateTime, false);

            if ($minutesDiff > 30) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hall allocation will be done 30 minutes before the exam starts.',
                    'invigilators_allotment' => [],
                ]);
            }
        }
        // If exam is in the future (after today), block access
        elseif ($examDateTime->isAfter($currentDateTime->endOfDay())) {
            return response()->json([
                'success' => false,
                'message' => 'Hall allocation will be done 30 minutes before the exam starts.',
                'invigilators_allotment' => [],
            ]);
        }
        // Get saved invigilators for this date and session
        $staffAllocation = CIStaffAllocation::where([
            'exam_id' => $validated['exam_id'],
            'exam_date' => $exam_date,
            'ci_id' => $user->ci_id,
        ])->first();

        if (!$staffAllocation || empty($staffAllocation->invigilators[$session])) {
            return response()->json([
                'success' => false,
                'message' => 'No invigilators found for this session',
            ], 404);
        }

        // Get session data
        $sessionData = $staffAllocation->invigilators[$session];

        // If halls are already allocated, return existing allocations
        if (!empty($sessionData['hall_allocations'])) {
            $hallAllocationsWithDetails = collect($sessionData['hall_allocations'])->map(function ($allocation) {
                $invigilator = Invigilator::where('invigilator_id', $allocation['invigilator_id'])->first();

                return [
                    'hall_code' => $allocation['hall_code'],
                    'invigilator_id' => $allocation['invigilator_id'],
                    'invigilator_name' => $invigilator->invigilator_name ?? '',
                    'invigilator_phone' => $invigilator->invigilator_phone ?? '',
                ];
            })->toArray();
            return response()->json([
                'success' => true,
                'message' => 'Existing hall allocations retrieved successfully',
                'invigilators_allotment' => [
                    'hall_allocations' => $hallAllocationsWithDetails,
                ],
            ]);
        }

        // Get invigilators array for this session
        $invigilatorIds = $sessionData['invigilators'];

        // Shuffle invigilator IDs randomly
        $shuffledInvigilators = $invigilatorIds;
        shuffle($shuffledInvigilators);

        // Generate hall allocations
        $hallAllocations = [];
        foreach ($shuffledInvigilators as $index => $invigilatorId) {
            // Generate hall code with leading zeros (001, 002, etc.)
            $hallNumber = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            $hallCode = $hallNumber;

            $hallAllocations[] = [
                'invigilator_id' => $invigilatorId,
                'hall_code' => $hallCode,
            ];
        }

        // Save the new hall allocations
        $sessionData['hall_allocations'] = $hallAllocations;
        $sessionData['allocation_timestamp'] = now()->toDateTimeString();

        // Update the stored data
        $currentData = $staffAllocation->invigilators;
        $currentData[$session] = $sessionData;

        $staffAllocation->invigilators = $currentData;
        $staffAllocation->save();
        $hallAllocationsWithDetails = collect($hallAllocations)->map(function ($allocation) {
            $invigilator = Invigilator::where('invigilator_id', $allocation['invigilator_id'])->first();

            return [
                'hall_code' => $allocation['hall_code'],
                'invigilator_id' => $allocation['invigilator_id'],
                'invigilator_name' => $invigilator->invigilator_name ?? '',
                'invigilator_phone' => $invigilator->invigilator_phone ?? '',
            ];
        })->toArray();
        return response()->json([
            'success' => true,
            'message' => 'Halls allocated successfully',
            'invigilators_allotment' => [
                'hall_allocations' => $hallAllocationsWithDetails,
            ],
        ]);
    }

    public function updateScribeDetails(Request $request, $examId, $examDate, $ciId)
    {
        $validated = $request->validate([
            'exam_sess_session' => 'required|string',
            'scribes' => 'required|array',
            'reg_no' => 'required|array',
        ]);

        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        if (!$user || !isset($user->ci_id)) {
            return back()->withErrors(['auth' => 'Unable to retrieve the authenticated user.']);
        }

        $staffAllocation = CIStaffAllocation::firstOrCreate([
            'exam_id' => $examId,
            'exam_date' => $examDate,
            'ci_id' => $ciId,
        ], [
            'scribes' => [],
            'created_at' => now()
        ]);

        $session = $validated['exam_sess_session'];
        $currentData = $staffAllocation->scribes ?: [];

        // Get existing assignments for this session or initialize empty array
        $existingSessionData = $currentData[$session]['scribe_assignments'] ?? [];

        // Convert existing assignments to associative array using reg_no as key for easy lookup
        $existingAssignments = [];
        foreach ($existingSessionData as $assignment) {
            $existingAssignments[$assignment['reg_no']] = $assignment;
        }

        // Process new assignments
        $timestamp = now()->toDateTimeString();
        foreach ($validated['reg_no'] as $index => $regNo) {
            if (isset($validated['scribes'][$index])) {
                // Create or update assignment
                $existingAssignments[$regNo] = [
                    'reg_no' => $regNo,
                    'scribe_id' => $validated['scribes'][$index],
                ];
            }
        }

        // Convert back to indexed array
        $allAssignments = array_values($existingAssignments);

        // Update session data
        $currentData[$session] = [
            'scribe_assignments' => $allAssignments,
            'last_updated' => $timestamp
        ];

        // Save the updated data
        $staffAllocation->scribes = $currentData;
        $staffAllocation->save();

        return redirect()->back()->with('success', 'Scribe details updated successfully.');
    }

    public function updateCIAssistantDetails(Request $request, $examId, $examDate, $ciId)
    {
        try {
            $validated = $request->validate([
                'assistants' => 'required|array|min:2|max:2',
                'exam_id' => 'required',
                'exam_sess_date' => 'required|date',
                'exam_sess_session' => 'required|in:FN,AN',
            ]);

            $role = session('auth_role');
            $guard = $role ? Auth::guard($role) : null;
            $user = $guard ? $guard->user() : null;

            if (!$user || !isset($user->ci_id)) {
                return back()->withErrors(['auth' => 'Unable to retrieve the authenticated user.']);
            }

            $staffAllocation = CIStaffAllocation::firstOrCreate([
                'exam_id' => $examId,
                'exam_date' => $examDate,
                'ci_id' => $ciId,
            ], [
                'assistants' => [],
                'created_at' => now()
            ]);

            $session = $validated['exam_sess_session'];
            $currentData = $staffAllocation->assistants ?: [];

            // Structure the session data
            $sessionData = [
                'assistant_ids' => $validated['assistants'],
                'timestamp' => now()->toDateTimeString()
            ];

            // Update or add new session while preserving other sessions
            $currentData[$session] = $sessionData;

            // Save the updated data
            $staffAllocation->assistants = $currentData;
            $staffAllocation->save();

            return redirect()->back()->with('success', 'CI Assistant details updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while updating CI Assistant details.']);
        }
    }
}
