<?php

namespace App\Http\Controllers;

use App\Models\CIMeetingAttendance;
use App\Models\ExamConfirmedHalls;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class CIMeetingController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
    }

 
    /**
     * Handle QR code scan and store attendance details
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleAttendanceQRCodeScan(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'qr_code' => 'required|string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            // Parse the QR code string
            $qrCodeData = $this->parseQRCodeString($request->input('qr_code'));
            $role = session('auth_role');
            $guard = $role ? Auth::guard($role) : null;
            $user = $guard ? $guard->user() : null;

            // Check if the ci and exam id is present in the exam confirmed halls table
            $examConfirmedHalls = ExamConfirmedHalls::where('exam_id', $qrCodeData['exam_id'])->where('ci_id', $user->ci_id)->first();

            if (!$examConfirmedHalls) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You are not authorized to record attendance for this exam',
                ], 403);
            }
            // Check if the user has already recorded attendance for this exam
            $attendance = CIMeetingAttendance::where('exam_id', $qrCodeData['exam_id'])
                ->where('ci_id', $user->ci_id)
                ->first();

            if ($attendance) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You have already recorded attendance for this exam',
                ], 400);
            }


            // Create attendance record
            $attendance = CIMeetingAttendance::create([
                'exam_id' => $qrCodeData['exam_id'],
                'district_code' => $qrCodeData['district_code'],
                'center_code' => $examConfirmedHalls->center_code,
                'ci_id' => $user->ci_id,
                'hall_code' => $examConfirmedHalls->hall_code,
                'adequacy_check' => [], // Optional
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Attendance recorded successfully',
                'data' => $attendance
            ], 200);

        } catch (\Exception $e) {
            // Handle any unexpected errors
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to record attendance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Parse the QR code string to extract details
     *
     * @param string $qrCodeString
     * @return array
     */
    private function parseQRCodeString(string $qrCodeString)
    {
        // Split the string by comma
        $parts = explode(', ', $qrCodeString);

        // Initialize an array to store parsed data
        $parsedData = [];

        // Parse each part
        foreach ($parts as $part) {
            // Split each part into key and value
            $keyValue = explode(': ', $part);

            if (count($keyValue) == 2) {
                $key = trim($keyValue[0]);
                $value = trim($keyValue[1]);

                // Map the keys to database column names
                switch ($key) {
                    case 'Exam ID':
                        $parsedData['exam_id'] = $value;
                        break;
                    case 'Meeting Date & Time':
                        $parsedData['meeting_date_time'] = $value;
                        break;
                    case 'District Code':
                        $parsedData['district_code'] = $value;
                        break;
                }
            }
        }

        // Validate that all required fields are present
        $requiredKeys = ['exam_id', 'meeting_date_time', 'district_code'];
        foreach ($requiredKeys as $key) {
            if (!isset($parsedData[$key])) {
                throw new \Exception("Missing required field: $key");
            }
        }

        return $parsedData;
    }

    public function updateAdequacyCheck(Request $request)
    {

        $validated = $request->validate([
            'exam_id' => 'required',
            'received_appointment_letter' => 'required',
            'received_packet' => 'required',
            'received_amount' => 'required',
        ]);
       
        try {
            // Get user info
            $role = session('auth_role');
            $guard = $role ? Auth::guard($role) : null;
            $user = $guard ? $guard->user() : null;

            // Check if the ci and exam id is present in the exam confirmed halls table
            $examConfirmedHalls = ExamConfirmedHalls::where('exam_id', $validated['exam_id'])->where('ci_id', $user->ci_id)->first();

            if (!$examConfirmedHalls) {
                return back()->with('error', 'You are not authorized to update adequacy check for this exam');
            }

            // Update the adequacy check
            $attendance = CIMeetingAttendance::where('exam_id', $validated['exam_id'])
                ->where('ci_id', $user->ci_id)
                ->first();

            if (!$attendance) {
                return back()->with('error', 'You have not recorded attendance for this exam');
            }
            //store validated as json array in db
            $attendance->adequacy_check = $validated;
            $attendance->save();
            // Return success response
            return back()->with('success', 'Adequacy check updated successfully');

        } catch (\Exception $e) {
            // Handle any unexpected errors
            return back()->with('error', 'Failed to update adequacy check: ' . $e->getMessage());

        }
    }

}