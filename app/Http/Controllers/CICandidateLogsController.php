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

    public function saveRemarkCandidates(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'exam_id' => 'required|numeric',
            'exam_sess_date' => 'required|date',
            'exam_sess_session' => 'required|string',
            'candidateRegNo' => 'nullable|array',
            'candidateRemarks' => 'nullable|array',
        ]);
        // dd($validated);
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

        // Prepare the remarks data as an array, grouped by session (FN, AN)
        $newRemarks = [
            'FN' => [],
            'AN' => [],
        ];

        if (isset($validated['candidateRegNo']) && isset($validated['candidateRemarks'])) {
            foreach ($validated['candidateRegNo'] as $index => $regNo) {
                // Append candidate remarks into FN or AN based on the session
                $session = strtoupper($sessions); // Ensure the session is in uppercase ('FN' or 'AN')
                if (array_key_exists($session, $newRemarks)) {
                    $newRemarks[$session][] = [
                        'registration_number' => $regNo,
                        'remark' => $validated['candidateRemarks'][$index],
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
            // Merge the new remarks with the existing remarks
            $existingRemarks = $candidateLog->candidate_remarks;

            // Merge FN and AN sessions separately
            foreach ($newRemarks as $session => $remarks) {
                if (isset($existingRemarks[$session])) {
                    $existingRemarks[$session] = array_merge($existingRemarks[$session], $remarks);
                } else {
                    $existingRemarks[$session] = $remarks;
                }
            }

            // Update the record
            $candidateLog->update([
                'candidate_remarks' => $existingRemarks,
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
                'candidate_remarks' => $newRemarks,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }

        return redirect()->back()->with('success', 'Candidate remarks saved successfully!');
    }

    public function updateRemarkCandidates(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'exam_id' => 'required|numeric',
            'exam_sess_date' => 'required|date',
            'exam_sess_session' => 'required|string',
            'candidateRegNo' => 'nullable|array',
            'candidateRemarks' => 'nullable|array',
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

        // Prepare the remarks data for the selected session (FN or AN)
        $newRemarks = [
            'FN' => [],
            'AN' => [],
        ];

        if (isset($validated['candidateRegNo']) && isset($validated['candidateRemarks'])) {
            foreach ($validated['candidateRegNo'] as $index => $regNo) {
                // Only add remarks to the selected session
                if (strtoupper($sessions) == 'FN') {
                    $newRemarks['FN'][] = [
                        'registration_number' => $regNo,
                        'remark' => $validated['candidateRemarks'][$index],
                    ];
                } elseif (strtoupper($sessions) == 'AN') {
                    $newRemarks['AN'][] = [
                        'registration_number' => $regNo,
                        'remark' => $validated['candidateRemarks'][$index],
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
            // If record exists, update the candidate remarks for the selected session
            $candidateLog->update([
                'candidate_remarks' => $newRemarks,
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
                'candidate_remarks' => $newRemarks,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }

        return redirect()->back()->with('success', 'Candidate remarks saved successfully!');
    }
    public function saveOMRRemark(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'exam_id' => 'required|numeric',
            'exam_sess_date' => 'required|date',
            'exam_sess_session' => 'required|string',
            'candidateRegNo' => 'nullable|array',
            'candidateRemarks' => 'nullable|array',
        ]);

        // Get exam details from request
        $exam_date = $request->input('exam_sess_date'); // Exam date
        $sessions = $request->input('exam_sess_session'); // Exam session (FN or AN)

        // Retrieve authenticated user and CI ID
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;
        $ci_id = $user ? $user->ci_id : null;

        // If user or CI ID is not found, return an error
        if (!$user || !$ci_id) {
            return back()->withErrors(['auth' => 'Unable to retrieve the authenticated user.']);
        }

        $timestamp = now()->toDateTimeString(); // Current timestamp

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

        // Prepare the remarks data as an array, grouped by session (FN, AN)
        $newRemarks = [
            'FN' => [],
            'AN' => [],
        ];

        // If candidate data and remarks exist, process and store
        if (isset($validated['candidateRegNo']) && isset($validated['candidateRemarks'])) {
            foreach ($validated['candidateRegNo'] as $index => $regNo) {
                // Ensure session is in uppercase ('FN' or 'AN')
                $session = strtoupper($sessions); // Validate the session

                if ($session == 'FN' || $session == 'AN') { // Ensure valid session
                    // Check if the registration number already exists in the current session (FN or AN)
                    $existingRemarks = CIcandidateLogs::where([
                        'exam_id' => $validated['exam_id'],
                        'center_code' => $centerCode,
                        'hall_code' => $hallCode,
                        'exam_date' => $exam_date,
                        'ci_id' => $ci_id,
                    ])->first();

                    if ($existingRemarks) {
                        // For FN or AN session, check if the registration number exists in the specific session
                        $existingSessionRemarks = $existingRemarks->omr_remarks[$session] ?? [];
                        $existingRegNos = array_column($existingSessionRemarks, 'registration_number');

                        if (in_array($regNo, $existingRegNos)) {
                            return redirect()->back()->withErrors(['error' => "Registration Number $regNo already exists in the OMR remarks for the $session session."]);
                        }
                    }

                    // Prepare the data for the new remarks entry, including the timestamp
                    $newRemarks[$session][] = [
                        'registration_number' => $regNo,
                        'remark' => $validated['candidateRemarks'][$index],
                        'timestamp' => $timestamp,  // Add the timestamp to each remark
                    ];
                }
            }
        }

        // Retrieve the existing record for OMR remarks if it exists
        $omrLog = CIcandidateLogs::where([
            'exam_id' => $validated['exam_id'],
            'center_code' => $centerCode,
            'hall_code' => $hallCode,
            'exam_date' => $exam_date,
            'ci_id' => $ci_id,
        ])->first();

        if ($omrLog) {
            // Merge new OMR remarks with the existing ones
            $existingOMRRemarks = $omrLog->omr_remarks;

            // Merge FN and AN sessions separately
            foreach ($newRemarks as $session => $remarks) {
                if (isset($existingOMRRemarks[$session])) {
                    $existingOMRRemarks[$session] = array_merge($existingOMRRemarks[$session], $remarks);
                } else {
                    $existingOMRRemarks[$session] = $remarks;
                }
            }

            // Update the existing OMR log with the new remarks
            $omrLog->update([
                'omr_remarks' => $existingOMRRemarks,
                'updated_at' => $timestamp,
            ]);
        } else {
            // If no existing record, create a new OMR log record
            CIcandidateLogs::create([
                'exam_id' => $validated['exam_id'],
                'center_code' => $centerCode,
                'hall_code' => $hallCode,
                'exam_date' => $exam_date,
                'ci_id' => $ci_id,
                'omr_remarks' => $newRemarks,  // Save the new remarks
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }

        return redirect()->back()->with('success', 'OMR Remarks saved successfully!');
    }
}
