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
        // Validate the incoming request data
        $validated = $request->validate([
            'exam_id' => 'required|numeric',
            'exam_sess_date' => 'required|date',
            'exam_sess_session' => 'required|string', // FN or AN
        ]);

        $exam_date = $request->input('exam_sess_date'); // Exam date
        $sessions = $request->input('exam_sess_session'); // Exam session

        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;
        $ci_id = $user ? $user->ci_id : null;

        if (!$user || !$ci_id) {
            return back()->withErrors(['auth' => 'Unable to retrieve the authenticated user.']);
        }

        $timestamp = now()->toDateTimeString();

        // Query the 'exam_confirmed_halls' table to get center_code and hall_code
        $examConfirmedHall = ExamConfirmedHalls::where('exam_id', $validated['exam_id'])
            ->where('exam_date', $exam_date)
            ->where('exam_session', $sessions)
            ->where('ci_id', $ci_id)
            ->first();

        if (!$examConfirmedHall) {
            return redirect()->back()->with('error', 'No matching record found.');
        }

        $centerCode = $examConfirmedHall->center_code;
        $hallCode = $examConfirmedHall->hall_code;

        // Find existing QpBoxLog record or create a new one
        $qpBoxLog = QpBoxLog::firstOrNew([
            'exam_id' => $validated['exam_id'],
            'exam_date' => $exam_date,
            'center_code' => $centerCode,
            'hall_code' => $hallCode,
            'ci_id' => $ci_id,
        ]);

        $newSessionData = [
            'session' => $sessions,
            'qp_box_open_time' => $timestamp,
        ];

        if ($qpBoxLog->exists && is_array($qpBoxLog->qp_timing_log)) {
            $existingTimings = $qpBoxLog->qp_timing_log;
            $sessionExists = false;

            foreach ($existingTimings as &$session) {
                if ($session['session'] === $sessions) {
                    $session['qp_box_open_time'] = $timestamp;
                    $sessionExists = true;
                    break;
                }
            }

            if (!$sessionExists) {
                $existingTimings[] = $newSessionData;
            }

            $qpBoxLog->qp_timing_log = $existingTimings;
        } else {
            $qpBoxLog->qp_timing_log = [$newSessionData];
        }

        if (!$qpBoxLog->exists) {
            $qpBoxLog->created_at = now();
        }

        $qpBoxLog->save();

        return redirect()->back()->with('success', 'Scan time saved successfully!');
    }
    public function saveqpboxdistributiontimeTime(Request $request)
{
    // Validate the incoming request data
    $validated = $request->validate([
        'exam_id' => 'required|numeric',
        'exam_sess_date' => 'required|date',
        'exam_sess_session' => 'required|string', // FN or AN
    ]);

    $exam_date = $request->input('exam_sess_date'); // Exam date
    $sessions = $request->input('exam_sess_session'); // Exam session

    $role = session('auth_role');
    $guard = $role ? Auth::guard($role) : null;
    $user = $guard ? $guard->user() : null;
    $ci_id = $user ? $user->ci_id : null;

    if (!$user || !$ci_id) {
        return back()->withErrors(['auth' => 'Unable to retrieve the authenticated user.']);
    }

    $timestamp = now()->toDateTimeString();

    // Query the 'exam_confirmed_halls' table to get center_code and hall_code
    $examConfirmedHall = ExamConfirmedHalls::where('exam_id', $validated['exam_id'])
        ->where('exam_date', $exam_date)
        ->where('exam_session', $sessions)
        ->where('ci_id', $ci_id)
        ->first();

    if (!$examConfirmedHall) {
        return redirect()->back()->with('error', 'No matching record found.');
    }

    $centerCode = $examConfirmedHall->center_code;
    $hallCode = $examConfirmedHall->hall_code;

    // Find existing QpBoxLog record or create a new one
    $qpBoxLog = QpBoxLog::firstOrNew([
        'exam_id' => $validated['exam_id'],
        'exam_date' => $exam_date,
        'center_code' => $centerCode,
        'hall_code' => $hallCode,
        'ci_id' => $ci_id,
    ]);

    $newSessionData = [
        'session' => $sessions,
        'qp_box_distribution_time' => $timestamp,
    ];

    if ($qpBoxLog->exists && is_array($qpBoxLog->qp_timing_log)) {
        $existingTimings = $qpBoxLog->qp_timing_log;
        $sessionExists = false;

        foreach ($existingTimings as &$session) {
            if ($session['session'] === $sessions) {
                // Update the distribution time without removing previous data
                $session['qp_box_distribution_time'] = $timestamp;
                $sessionExists = true;
                break;
            }
        }

        if (!$sessionExists) {
            $existingTimings[] = $newSessionData;
        }

        $qpBoxLog->qp_timing_log = $existingTimings;
    } else {
        $qpBoxLog->qp_timing_log = [$newSessionData];
    }

    if (!$qpBoxLog->exists) {
        $qpBoxLog->created_at = now();
    }

    $qpBoxLog->save();

    return redirect()->back()->with('success', 'Distribution time saved successfully!');
}

}
