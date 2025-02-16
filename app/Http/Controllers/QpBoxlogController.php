<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\ExamConfirmedHalls;
use App\Models\QpBoxLog;

use Illuminate\Http\Request;

class QpBoxlogController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
    }
    public function saveTime(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|numeric',
            'exam_sess_date' => 'required|date',
            'exam_sess_session' => 'required|string',
        ]);

        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;
        $ci_id = $user ? $user->ci_id : null;

        if (!$user || !$ci_id) {
            return back()->withErrors(['auth' => 'Unable to retrieve the authenticated user.']);
        }

        $examConfirmedHall = ExamConfirmedHalls::where('exam_id', $validated['exam_id'])
            ->where('exam_date', $validated['exam_sess_date'])
            ->where('exam_session', $validated['exam_sess_session'])
            ->where('ci_id', $ci_id)
            ->first();

        if (!$examConfirmedHall) {
            return redirect()->back()->with('error', 'No matching record found.');
        }

        $qpBoxLog = QpBoxLog::firstOrCreate([
            'exam_id' => $validated['exam_id'],
            'exam_date' => $validated['exam_sess_date'],
            'center_code' => $examConfirmedHall->center_code,
            'hall_code' => $examConfirmedHall->hall_code,
            'ci_id' => $ci_id,
        ], [
            'qp_timing_log' => [],
            'created_at' => now()
        ]);

        $session = $validated['exam_sess_session'];
        $currentData = $qpBoxLog->qp_timing_log ?: [];

        // Get existing session data or initialize new
        $sessionData = $currentData[$session] ?? [];

        // Update open time while preserving distribution time
        $sessionData = array_merge([
            'qp_box_open_time' => now()->toDateTimeString(),
            'qp_box_distribution_time' => null,
        ], $sessionData);

        // Ensure the timestamp is updated
        $sessionData['qp_box_open_time'] = now()->toDateTimeString();

        // Update session data while preserving other sessions
        $currentData[$session] = $sessionData;

        // Save the updated data
        $qpBoxLog->qp_timing_log = $currentData;
        $qpBoxLog->save();

        return redirect()->back()->with('success', 'Scan time saved successfully!');
    }
    public function saveqpboxdistributiontimeTime(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|numeric',
            'exam_sess_date' => 'required|date',
            'exam_sess_session' => 'required|string',
        ]);

        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;
        $ci_id = $user ? $user->ci_id : null;

        if (!$user || !$ci_id) {
            return back()->withErrors(['auth' => 'Unable to retrieve the authenticated user.']);
        }

        // Query confirmed halls
        $examConfirmedHall = ExamConfirmedHalls::where('exam_id', $validated['exam_id'])
            ->where('exam_date', $validated['exam_sess_date'])
            ->where('exam_session', $validated['exam_sess_session'])
            ->where('ci_id', $ci_id)
            ->first();

        if (!$examConfirmedHall) {
            return redirect()->back()->with('error', 'No matching record found.');
        }

        // Find or create QpBoxLog record
        $qpBoxLog = QpBoxLog::firstOrCreate([
            'exam_id' => $validated['exam_id'],
            'exam_date' => $validated['exam_sess_date'],
            'center_code' => $examConfirmedHall->center_code,
            'hall_code' => $examConfirmedHall->hall_code,
            'ci_id' => $ci_id,
        ], [
            'qp_timing_log' => [],
            'created_at' => now()
        ]);

        $session = $validated['exam_sess_session'];
        $currentData = $qpBoxLog->qp_timing_log ?: [];

        // Get existing session data or initialize new
        $sessionData = $currentData[$session] ?? [
            'qp_box_open_time' => null,
        ];

        // Update distribution time while preserving other fields
        $sessionData['qp_box_distribution_time'] = now()->toDateTimeString();

        // Update session data while preserving other sessions
        $currentData[$session] = $sessionData;

        // Save updated data
        $qpBoxLog->qp_timing_log = $currentData;
        $qpBoxLog->save();

        return redirect()->back()->with('success', 'Distribution time saved successfully!');
    }

}
