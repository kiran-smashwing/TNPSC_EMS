<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\ExamConfirmedHalls;
use App\Models\CICandidateLogs;
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
        $validated = $request->validate([
            'exam_id' => 'required|numeric',
            'exam_sess_date' => 'required|date',
            'exam_sess_session' => 'required|string',
            'candidateRegNo' => 'nullable|array',
            'candidateName' => 'nullable|array',
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

        // Find or create candidate log
        $candidateLog = CICandidateLogs::firstOrCreate([
            'exam_id' => $validated['exam_id'],
            'center_code' => $examConfirmedHall->center_code,
            'hall_code' => $examConfirmedHall->hall_code,
            'exam_date' => $validated['exam_sess_date'],
            'ci_id' => $ci_id,
        ], [
            'additional_details' => [],
            'created_at' => now()
        ]);

        $session = $validated['exam_sess_session'];
        $currentData = $candidateLog->additional_details ?: [];

        // Get existing session data or initialize new
        $sessionData = $currentData[$session] ?? [
            'candidates' => [],
            'timestamp' => now()->toDateTimeString()
        ];

        // Prepare new candidate details
        $newCandidates = [];
        if (isset($validated['candidateRegNo']) && isset($validated['candidateName'])) {
            foreach ($validated['candidateRegNo'] as $index => $regNo) {
                $newCandidates[] = [
                    'registration_number' => $regNo,
                    'candidate_name' => $validated['candidateName'][$index],
                ];
            }
        }

        // Merge existing and new candidates, ensuring uniqueness by registration number
        $existingCandidates = $sessionData['candidates'] ?? [];
        $existingRegNos = array_column($existingCandidates, 'registration_number');

        foreach ($newCandidates as $candidate) {
            if (!in_array($candidate['registration_number'], $existingRegNos)) {
                $existingCandidates[] = $candidate;
                $existingRegNos[] = $candidate['registration_number'];
            }
        }

        // Update session data
        $sessionData = [
            'candidates' => $existingCandidates,
            'timestamp' => now()->toDateTimeString()
        ];

        // Update session while preserving other sessions
        $currentData[$session] = $sessionData;

        // Save the updated data
        $candidateLog->additional_details = $currentData;
        $candidateLog->updated_at = now();
        $candidateLog->save();

        return redirect()->back()->with('success', 'Additional candidates saved successfully!');
    }

    public function saveRemarkCandidates(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|numeric',
            'exam_sess_date' => 'required|date',
            'exam_sess_session' => 'required|string',
            'candidateRegNo' => 'nullable|array',
            'candidateRemarks' => 'nullable|array',
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

        // Find or create candidate log
        $candidateLog = CICandidateLogs::firstOrCreate([
            'exam_id' => $validated['exam_id'],
            'center_code' => $examConfirmedHall->center_code,
            'hall_code' => $examConfirmedHall->hall_code,
            'exam_date' => $validated['exam_sess_date'],
            'ci_id' => $ci_id,
        ], [
            'candidate_remarks' => [],
            'created_at' => now()
        ]);

        $session = $validated['exam_sess_session'];
        $currentData = $candidateLog->candidate_remarks ?: [];

        // Get existing session data or initialize new
        $sessionData = $currentData[$session] ?? [
            'remarks' => [],
            'timestamp' => now()->toDateTimeString()
        ];

        // Prepare new remarks
        $newRemarks = [];
        if (isset($validated['candidateRegNo']) && isset($validated['candidateRemarks'])) {
            foreach ($validated['candidateRegNo'] as $index => $regNo) {
                $newRemarks[] = [
                    'registration_number' => $regNo,
                    'remark' => $validated['candidateRemarks'][$index],
                ];
            }
        }

        // Merge existing and new remarks, ensuring uniqueness by registration number
        $existingRemarks = $sessionData['remarks'] ?? [];
        $existingRegNos = array_column($existingRemarks, 'registration_number');

        foreach ($newRemarks as $remark) {
            if (!in_array($remark['registration_number'], $existingRegNos)) {
                $existingRemarks[] = $remark;
                $existingRegNos[] = $remark['registration_number'];
            } else {
                // Update existing remark for this registration number
                foreach ($existingRemarks as $key => $existingRemark) {
                    if ($existingRemark['registration_number'] === $remark['registration_number']) {
                        $existingRemarks[$key] = $remark;
                        break;
                    }
                }
            }
        }

        // Update session data
        $sessionData = [
            'remarks' => $existingRemarks,
            'timestamp' => now()->toDateTimeString()
        ];

        // Update session while preserving other sessions
        $currentData[$session] = $sessionData;

        // Save the updated data
        $candidateLog->candidate_remarks = $currentData;
        $candidateLog->updated_at = now();
        $candidateLog->save();

        return redirect()->back()->with('success', 'Candidate remarks saved successfully!');
    }

    public function saveOMRRemark(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|numeric',
            'exam_sess_date' => 'required|date',
            'exam_sess_session' => 'required|string',
            'candidateRegNo' => 'nullable|array',
            'candidateRemarks' => 'nullable|array',
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

        // Find or create candidate log
        $candidateLog = CICandidateLogs::firstOrCreate([
            'exam_id' => $validated['exam_id'],
            'center_code' => $examConfirmedHall->center_code,
            'hall_code' => $examConfirmedHall->hall_code,
            'exam_date' => $validated['exam_sess_date'],
            'ci_id' => $ci_id,
        ], [
            'omr_remarks' => [],
            'created_at' => now()
        ]);

        $session = $validated['exam_sess_session'];
        $currentData = $candidateLog->omr_remarks ?: [];

        // Get existing session data or initialize new
        $sessionData = $currentData[$session] ?? [
            'remarks' => [],
            'timestamp' => now()->toDateTimeString()
        ];

        // Prepare new remarks
        if (isset($validated['candidateRegNo']) && isset($validated['candidateRemarks'])) {
            $existingRegNos = array_column($sessionData['remarks'], 'reg_no');

            foreach ($validated['candidateRegNo'] as $index => $regNo) {
                if (!in_array($regNo, $existingRegNos)) {
                    $sessionData['remarks'][] = [
                        'reg_no' => $regNo,
                        'remark' => $validated['candidateRemarks'][$index],
                    ];
                } else {
                    return redirect()->back()->withErrors(['error' => "Registration Number $regNo already exists in the OMR remarks for this session."]);
                }
            }
        }

        // Update timestamp
        $sessionData['timestamp'] = now()->toDateTimeString();

        // Update session while preserving other sessions
        $currentData[$session] = $sessionData;

        // Save the updated data
        $candidateLog->omr_remarks = $currentData;
        $candidateLog->updated_at = now();
        $candidateLog->save();

        return redirect()->back()->with('success', 'OMR Remarks saved successfully!');
    }

    public function saveCandidateAttendance(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'exam_id' => 'required|numeric',
            'exam_sess_date' => 'required|date',
            'exam_sess_session' => 'required|string',
            'present' => 'nullable|array',
            'absent' => 'nullable|array',
            'alloted_count' => 'required|numeric',
        ]);

        // Extract exam details
        $exam_date = $validated['exam_sess_date'];
        $sessions = $validated['exam_sess_session']; // Session (FN/AN)

        // Retrieve authenticated user and CI ID
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;
        $ci_id = $user ? $user->ci_id : null;

        if (!$user || !$ci_id) {
            return redirect()->back()->withErrors(['error' => 'Unable to retrieve the authenticated user.']);
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

        // Check if attendance data already exists for this session (FN/AN)
        $existingAttendance = CICandidateLogs::where([
            'exam_id' => $validated['exam_id'],
            'center_code' => $centerCode,
            'hall_code' => $hallCode,
            'exam_date' => $exam_date,
            'ci_id' => $ci_id,
        ])->first();

        if ($existingAttendance) {
            // Check if attendance for the specific session already exists
            $existingData = $existingAttendance->candidate_attendance ?? [];
            if (isset($existingData[$sessions])) {
                return redirect()->back()->withErrors([
                    'error' => "Attendance for the $sessions session has already been recorded. No duplicate entries allowed.",
                ]);
            }
        }

        // Prepare attendance data
        $attendanceData = [
            $sessions => [
                'present' => array_sum($validated['present'] ?? []), // Sum of all present values
                'absent' => array_sum($validated['absent'] ?? []),   // Sum of all absent values
                'alloted_count' => $validated['alloted_count'],
                'timestamp' => $timestamp,
            ],
        ];

        // Ensure present + absent matches the allotted count
        if (($attendanceData[$sessions]['present'] + $attendanceData[$sessions]['absent']) != $validated['alloted_count']) {
            return redirect()->back()->withErrors([
                'error' => 'The sum of present and absent must equal the allotted count.',
            ]);
        }

        if ($existingAttendance) {
            // Merge new attendance data with existing records
            $existingAttendanceData = $existingAttendance->candidate_attendance ?? [];
            $existingAttendanceData = array_merge($existingAttendanceData, $attendanceData);

            $existingAttendance->update([
                'candidate_attendance' => $existingAttendanceData,
                'updated_at' => $timestamp,
            ]);
        } else {
            // Create a new attendance record if it doesn't exist
            CICandidateLogs::create([
                'exam_id' => $validated['exam_id'],
                'center_code' => $centerCode,
                'hall_code' => $hallCode,
                'exam_date' => $exam_date,
                'ci_id' => $ci_id,
                'candidate_attendance' => $attendanceData,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }

        return redirect()->back()->with('success', 'Candidate attendance saved successfully!');
    }
}
