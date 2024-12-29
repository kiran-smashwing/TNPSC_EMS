<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\ExamConfirmedHalls;
use App\Models\CIcandidateLogs;
use Illuminate\Http\Request;


class CICandidateLogsController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
    }
    public function saveAdditionalCandidates(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'exam_id' => 'required|numeric',
            'exam_sess_date' => 'required|date',
            'exam_sess_session' => 'required|string',
            'candidateRegNo' => 'nullable|array',
            'candidateName' => 'nullable|array',
        ]);

        $exam_date = $request->input('exam_sess_date'); // Exam date
        $sessions = $request->input('exam_sess_session'); // Exam session (FN or AN)

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

        // Prepare the additional details data as an array, grouped by session (FN, AN)
        $newDetails = [
            'FN' => [],
            'AN' => [],
        ];

        if (isset($validated['candidateRegNo']) && isset($validated['candidateName'])) {
            foreach ($validated['candidateRegNo'] as $index => $regNo) {
                // Append candidate details into FN or AN based on the session
                $session = strtoupper($sessions); // Ensure the session is in uppercase ('FN' or 'AN')
                if (array_key_exists($session, $newDetails)) {
                    $newDetails[$session][] = [
                        'registration_number' => $regNo,
                        'candidate_name' => $validated['candidateName'][$index],
                    ];
                }
            }
        }

        // Retrieve the existing record if it exists
        $candidateLog = CIcandidateLogs::where([
            'exam_id' => $validated['exam_id'],
            'center_code' => $centerCode,
            'hall_code' => $hallCode,
            'exam_date' => $exam_date,
            'ci_id' => $ci_id,
        ])->first();

        if ($candidateLog) {
            // Merge the new data with the existing data
            $existingDetails = $candidateLog->additional_details;

            // Merge FN and AN sessions separately
            foreach ($newDetails as $session => $candidates) {
                if (isset($existingDetails[$session])) {
                    $existingDetails[$session] = array_merge($existingDetails[$session], $candidates);
                } else {
                    $existingDetails[$session] = $candidates;
                }
            }

            // Update the record
            $candidateLog->update([
                'additional_details' => $existingDetails,
                'updated_at' => $timestamp,
            ]);
        } else {
            // If no existing record, create a new one
            $candidateLog = CIcandidateLogs::create([
                'exam_id' => $validated['exam_id'],
                'center_code' => $centerCode,
                'hall_code' => $hallCode,
                'exam_date' => $exam_date,
                'ci_id' => $ci_id,
                'additional_details' => $newDetails,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }

        return redirect()->back()->with('success', 'Additional candidates saved successfully!');
    }
}
