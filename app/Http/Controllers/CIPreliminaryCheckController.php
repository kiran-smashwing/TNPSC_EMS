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
        // Validate the incoming data
        // dd($request->all());
        $validated = $request->validate([
            'checklist' => 'required|array',
            'exam_id' => 'required|numeric',
            'exam_sess_date' => 'required',
            'exam_sess_session' => 'required',
            
        ]);
        dd($validated);

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
}
