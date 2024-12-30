<?php

namespace App\Http\Controllers;

use App\Models\ExamAssignment;
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
    public function saveinvigilatoreDetails(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'exam_id' => 'required',
            'exam_sess_date' => 'required|date',
            'exam_sess_session' => 'required|string',
            'invigilators' => 'nullable|array',
        ]);

        // Prepare the session data
        $exam_date = $request->input('exam_sess_date');
        $sessions = $request->input('exam_sess_session');

        // Get authenticated user
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        if (!$user || !isset($user->ci_id)) {
            return back()->withErrors(['auth' => 'Unable to retrieve the authenticated user.']);
        }

        $timestamp = now()->toDateTimeString();

        // Create the new session data structure
        $newSessionData = [
            'session' => $sessions,
            'invigilators' => $validated['invigilators'],
            'timestamp' => $timestamp,
        ];

        // Find existing record or create new one
        $examAssignment = ExamAssignment::firstOrNew([
            'exam_id' => $validated['exam_id'],
            'exam_date' => $exam_date,
            'ci_id' => $user->ci_id,
        ]);

        // Handle existing invigilators data
        if ($examAssignment->exists && $examAssignment->invigilators) {
            // If existing data is not an array, convert it to array
            $existingData = is_array($examAssignment->invigilators)
                ? $examAssignment->invigilators
                : [$examAssignment->invigilators];

            // Append new session data to existing data
            $existingData[] = $newSessionData;
            $examAssignment->invigilators = $existingData;
        } else {
            // If no existing data, initialize with the new session data
            $examAssignment->invigilators = [$newSessionData];
        }

        // Set or update created_at timestamp
        if (!$examAssignment->exists) {
            $examAssignment->created_at = now();
        }

        $examAssignment->save();

        return redirect()->back()->with('success', 'Invigilators Added successfully.');
    }

    public function updateInvigilatorDetails(Request $request, $examId, $examDate, $ciId)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'exam_sess_date' => 'required|date',
            'exam_sess_session' => 'required|string',
            'invigilators' => 'nullable|array',
        ]);

        // Get the authenticated user
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        if (!$user || !isset($user->ci_id)) {
            return back()->withErrors(['auth' => 'Unable to retrieve the authenticated user.']);
        }

        // Find the existing exam assignment record
        $examAssignment = ExamAssignment::where([
            ['exam_id', '=', $examId],
            ['exam_date', '=', $validated['exam_sess_date']],
            ['ci_id', '=', $user->ci_id]
        ])->first();

        // If the assignment doesn't exist, return an error
        if (!$examAssignment) {
            return back()->withErrors(['error' => 'Assignment not found']);
        }

        // Dump and Die to view the existing invigilators (old data)
        // dd('Old Data:', $examAssignment->invigilators);

        // Prepare the new session data
        $newSessionData = [
            'session' => $validated['exam_sess_session'],
            'invigilators' => $validated['invigilators'],
            'timestamp' => now()->toDateTimeString(),
        ];

        // Update the invigilators data (replace or append as needed)
        $existingData = $examAssignment->invigilators;

        // If there's existing data, update it
        if (is_array($existingData)) {
            foreach ($existingData as &$session) {
                if ($session['session'] == $newSessionData['session']) {
                    $session['invigilators'] = $newSessionData['invigilators'];
                    break;
                }
            }
        } else {
            // If no existing data, initialize the invigilators with the new session data
            $existingData = [$newSessionData];
        }

        // Dump and Die to view the new data before saving
        // dd('New Data:', $existingData);

        // Save the updated invigilators data
        $examAssignment->invigilators = $existingData;
        $examAssignment->save();

        return redirect()->back()->with('success', 'Invigilator details updated successfully.');
        // return redirect()->route('exam.details', ['examId' => $examId])
        //     ->with('success', 'Invigilator details updated successfully.');
    }

    public function updateScribeDetails(Request $request, $examId, $examDate, $ciId)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'exam_sess_session' => 'required|string', // The session for the exam
            'scribes' => 'nullable|array', // The scribes array to be added
            'reg_no' => 'nullable|array', // The scribes array to be added
        ]);

        // Dump the validated data to inspect it
        //  dd('Validated Data:', $validated);

        // Get the authenticated user
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        if (!$user || !isset($user->ci_id)) {
            return back()->withErrors(['auth' => 'Unable to retrieve the authenticated user.']);
        }

        // Find the existing CI staff allocation record using exam_id, exam_date, and ci_id
        $staffAllocation = ExamAssignment::where([
            ['exam_id', '=', $examId],
            ['exam_date', '=', $examDate],  // Use $examDate directly here
            ['ci_id', '=', $ciId],          // Use $ciId directly here
        ])->first();

        // Dump the staff allocation data to inspect it
        // dd('Staff Allocation Data:', $staffAllocation);

        // If the allocation doesn't exist, return an error
        if (!$staffAllocation) {
            return back()->withErrors(['error' => 'Staff allocation not found']);
        }

        // Prepare the new session data to add
        $newScribeData = [
            'session' => $validated['exam_sess_session'],  // The session to be added
            'scribes' => $validated['scribes'], // The scribes to be added
            'reg_no'  => $validated['reg_no'],
            'timestamp' => now()->toDateTimeString(),  // Record the timestamp of the update
        ];

        // Dump the new scribe data to inspect it
        // dd('New Scribe Data:', $newScribeData);

        // Add the new scribe data to the existing scribes data
        $existingData = $staffAllocation->scribes;

        // Dump the existing scribes data to inspect it
        // dd('Existing Scribes Data:', $existingData);

        // If no existing scribes data, initialize it with the new data
        if (is_null($existingData)) {
            $existingData = [$newScribeData];
        } else {
            // If existing scribes data exists, add the new scribe data
            $existingData[] = $newScribeData;
        }

        // Dump the updated data before saving to inspect it
        // dd('Updated Data Before Save:', $existingData);

        // Save the updated scribes data back to the record
        $staffAllocation->scribes = $existingData;
        $staffAllocation->save();

        // Return a success message after updating
        return redirect()->back()->with('success', 'Scribe details added successfully.');
    }

    public function updateCIAssistantDetails(Request $request, $examId, $examDate, $ciId)
    {
        try {
            // Validate the incoming request data
            $validated = $request->validate([
                'assistants' => 'required|array|min:2|max:2', // Ensure exactly 2 assistants are selected
                'exam_id' => 'required',
                'exam_sess_date' => 'required|date',
                'exam_sess_session' => 'required|in:FN,AN', // Ensure session is either FN or AN
            ]);

            // Get the authenticated user
            $role = session('auth_role');
            $guard = $role ? Auth::guard($role) : null;
            $user = $guard ? $guard->user() : null;

            if (!$user || !isset($user->ci_id)) {
                return back()->withErrors(['auth' => 'Unable to retrieve the authenticated user.']);
            }

            // Find the existing CI staff allocation record
            $staffAllocation = ExamAssignment::where([
                ['exam_id', '=', $examId],
                ['exam_date', '=', $examDate],
                ['ci_id', '=', $ciId],
            ])->first();

            if (!$staffAllocation) {
                return back()->withErrors(['error' => 'Staff allocation not found']);
            }

            // Prepare the new CI assistants data
            $newAssistantData = [
                'session' => $validated['exam_sess_session'],
                'assistants' => $validated['assistants'],
                'timestamp' => now()->toDateTimeString(),
            ];

            // Initialize existing assistants data if null
            $existingAssistants = $staffAllocation->assistants ?? [];

            // Append or update based on the session (FN or AN)
            $existingAssistants[] = $newAssistantData;

            // Save the updated assistants data back to the record
            $staffAllocation->assistants = $existingAssistants;
            $staffAllocation->save();

            return redirect()->back()->with('success', "CI Assistant details for session updated successfully.");
        } catch (\Exception $e) {
            // Log::error('CI Assistant Update Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'An error occurred while updating CI Assistant details.']);
        }
    }
}
