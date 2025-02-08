<?php

namespace App\Http\Controllers;

use App\Models\AlertNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ExamConfirmedHalls;
use App\Events\EmergencyAlertEvent;
use App\Events\AdequacyCheckEvent;

class AlertNotificationController extends Controller
{
    /**
     * Save Emergency Alert Notification
     */
    public function saveEmergencyAlert(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'exam_id' => 'required|numeric',
            'exam_sess_date' => 'required|date',
            'exam_sess_session' => 'required|string',
            'emergency_alert_type' => 'required|string',
            'emergency_alert_remarks' => 'nullable|string'
        ]);
        // Get exam details from request
        $exam_date = $request->input('exam_sess_date');
        $sessions = $request->input('exam_sess_session');

        // Retrieve authenticated user and CI ID
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;
        $ci_id = $user ? $user->ci_id : null;

        if (!$user || !$ci_id) {
            return back()->withErrors(['auth' => 'Unable to retrieve the authenticated user.']);
        }

        // Query the 'exam_confirmed_halls' table to get center_code and hall_code
        $examConfirmedHall = ExamConfirmedHalls::where('exam_id', $validated['exam_id'])
            ->where('exam_date', $exam_date)
            ->where('exam_session', $sessions)
            ->where('ci_id', $ci_id)
            ->first();

        if (!$examConfirmedHall) {
            return redirect()->back()->with('error', 'No matching record found.');
        }

        $districtCode = $examConfirmedHall->district_code;
        $centerCode = $examConfirmedHall->center_code;
        $hallCode = $examConfirmedHall->hall_code;

        // Store alert notification in the database
        $alertNotification = AlertNotification::create([
            'exam_id' => $validated['exam_id'],
            'district_code' => $districtCode,
            'center_code' => $centerCode,
            'hall_code' => $hallCode,
            'ci_id' => $ci_id,
            'exam_date' => $exam_date,
            'exam_session' => $sessions,
            'alert_type' => 'Emergency Alert',
            'details' => $validated['emergency_alert_type'],
            'remarks' => $request->input('emergency_alert_remarks'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $alertTypeTitles = [
            'malpractice' => 'Malpractice Reported',
            'attendance_sheets_missing' => 'Attendance Sheets Missing',
            'questions_not_printed_in_order' => 'Questions Not Printed in Order',
            'omr_answer_sheet_missing' => 'OMR/Answer Sheet Missing',
            'others' => 'Other Issues',
        ];
        // Retrieve the correct title for the emergency_alert_type
        $alertTypeTitle = $alertTypeTitles[$validated['emergency_alert_type']] ?? 'Unknown Alert Type';


        // Prepare data to broadcast.
        $alertData = [
            'id' => $alertNotification->id,
            'district' => $examConfirmedHall->district->district_name,
            'center' => $examConfirmedHall->center->center_name,
            'venue' => $examConfirmedHall->venue->venue_name,
            'details' => $alertTypeTitle,
            'remarks' => $request->input('emergency_alert_remarks'),
            'timestamp' => now()->toDateTimeString(),
        ];
        // In your controller where you dispatch the event
        \Log::info('Dispatching emergency alert', ['data' => $alertData]);
        event(new EmergencyAlertEvent($alertData));
        \Log::info('Emergency alert dispatched');
        // Dispatch the event.
        // event(new EmergencyAlertEvent($alertData));


        return redirect()->back()->with('success', 'Emergency Alert saved successfully!');
    }
    /**
     * Save Adequacy Check Notification
     */
    public function saveAdequacyCheck(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'exam_id' => 'required|numeric',
            'exam_sess_date' => 'required|date',
            'exam_sess_session' => 'required|string',
            'adequacy_check_type' => 'required|string',
            'adequacy_check_remarks' => 'nullable|string'
        ]);

        // Get exam details from request
        $exam_date = $request->input('exam_sess_date');
        $sessions = $request->input('exam_sess_session');

        // Retrieve authenticated user and CI ID
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;
        $ci_id = $user ? $user->ci_id : null;

        if (!$user || !$ci_id) {
            return back()->withErrors(['auth' => 'Unable to retrieve the authenticated user.']);
        }

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

        // Store alert notification in the database
        AlertNotification::create([
            'exam_id' => $validated['exam_id'],
            'district_code' => $centerCode,
            'center_code' => $hallCode,
            'hall_code' => $hallCode,
            'ci_id' => $ci_id,
            'exam_date' => $exam_date,
            'exam_session' => $sessions,
            'alert_type' => 'Adequacy Check',
            'details' => $validated['adequacy_check_type'],
            'remarks' => $request->input('adequacy_check_remarks'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Adequacy Check saved successfully!');
    }
}
