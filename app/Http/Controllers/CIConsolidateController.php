<?php

namespace App\Http\Controllers;

use App\Models\Scribe;
use Illuminate\Support\Facades\DB;
use App\Models\Currentexam;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;

class CIConsolidateController extends Controller
{
    /**
     * Generate a PDF Report.
     */
    public function generateReport($examId, $exam_date, $exam_session)
    {
        // Get the role and user from the session
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        if (!$user) {
            abort(403, 'Unauthorized action.');
        }
        $exam_session_type = $exam_session;
        $exam_date = $exam_date;
        // dd($exam_date);
        // Retrieve the exam data
        $exam_data = Currentexam::with('examservice')
            ->where('exam_main_no', $examId)
            ->first();
        if (!$exam_data) {
            abort(404, 'Exam data not found.');
        }
        $hall_code = DB::table('exam_confirmed_halls')
            ->where('exam_id', $examId)
            ->where('district_code', $user->ci_district_id)
            ->where('center_code', $user->ci_center_id)
            ->where('venue_code', $user->ci_venue_id)
            ->where('ci_id', $user->ci_id)
            ->pluck('hall_code')
            ->first();
        //CI-Qp box Log to get this all data
        $qp_box_timing = DB::table('ci_qp_box_log')
            ->where('exam_id', $examId)
            ->where('center_code', $user->ci_center_id)
            ->where('ci_id', $user->ci_id)
            ->where('hall_code', $hall_code)
            ->where('exam_date', $exam_date)
            ->first();

        $orm_remarks = DB::table('ci_candidate_logs')
            ->where('exam_id', $examId)
            ->where('center_code', $user->ci_center_id)
            ->where('ci_id', $user->ci_id)
            ->where('hall_code', $hall_code)
            ->where('exam_date', $exam_date)
            ->first();
        // dd($orm_remarks);
        $ci_checklist_answer = DB::table('ci_checklist_answers')
            ->where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->where('center_code', $user->ci_center_id)
            ->where('hall_code', $hall_code)
            ->first();
        // dd($ci_checklist_answer);
        $scribe_allocation = DB::table('ci_staff_allocation')
            ->where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->where('exam_date', $exam_date)
            ->first();
        // Decode the 'scribes' JSON field
        $session = $exam_session_type;

        if ($ci_checklist_answer) {
            // Decode the 'videography_answer' JSON field into an array
            $videography_data = json_decode($ci_checklist_answer->videography_answer, true);
            $consolidate_answer_data = json_decode($ci_checklist_answer->consolidate_answer, true);

            // Initialize an empty array for the checklist data to pass to the view
            $checklist_videography_data = [];
            $checklist_consolidate_data = [];

            // Check if 'sessions' is set in the decoded data
            if (isset($videography_data['sessions'])) {
                // Loop through the sessions
                foreach ($videography_data['sessions'] as $sessionData) {
                    // Ensure session type (AN or FN) and match with the current session
                    if (isset($sessionData['session'])) {
                        // Check if the session matches the current session (AN or FN)
                        if (($sessionData['session'] === 'FN' && $exam_session_type  == 'FN') ||
                            ($sessionData['session'] === 'AN' && $exam_session_type  == 'AN')
                        ) {

                            // Add data to the checklist
                            foreach ($sessionData['checklist'] as $checklistItem) {
                                $checklist_videography_data[] = [
                                    'checklist_id' => $checklistItem['checklist_id'] ?? 'N/A',
                                    'description' => $checklistItem['description'] ?? 'N/A',
                                    'inspection_staff' => $checklistItem['inspection_staff'] ?? 'N/A',
                                    'exam_date' => $sessionData['exam_date'] ?? 'N/A',
                                    'timestamp' => $sessionData['timestamp'] ?? 'N/A',
                                ];
                            }
                        }
                    }
                }
                if (isset($consolidate_answer_data['sessions'])) {
                    foreach ($consolidate_answer_data['sessions'] as $sessionData) {
                        if (isset($sessionData['session']) && (($sessionData['session'] === 'FN' && $exam_session_type == 'FN') ||
                            ($sessionData['session'] === 'AN' && $exam_session_type == 'AN'))) {
                            foreach ($sessionData['checklist'] as $checklistItem) {
                                $checklist_consolidate_data[] = [
                                    'checklist_id' => $checklistItem['description'], // Using 'description' as checklist_id
                                    'status' => $checklistItem['status'] == '1' ? 'Yes' : 'No', // Status for consolidate_answer
                                    'exam_date' => $sessionData['exam_date'] ?? 'N/A',
                                    'timestamp' => $sessionData['timestamp'] ?? 'N/A',
                                ];
                            }
                        }
                    }
                }
                //  dd($checklist_consolidate_data);
                $scribes_data = json_decode($scribe_allocation->scribes, true);
                if ($scribes_data) {
                    $all_scribe_details = [];

                    foreach ($scribes_data as $scribe) {
                        // Check if the session matches the current $exam_session_type
                        $current_session = $scribe['session']; // Default to FN if session is not provided

                        if ($current_session !== $session) {
                            continue; // Skip this scribe if the session doesn't match
                        }

                        foreach ($scribe['scribes'] as $scribe_id) {
                            $scribe_details = Scribe::find($scribe_id);

                            foreach ($scribe['reg_no'] as $reg_no) {
                                if ($scribe_details) {
                                    $all_scribe_details[] = $reg_no . ' (Scribe: ' . $scribe_details->scribe_name . '/' . $scribe_details->scribe_phone . ')';
                                } else {
                                    $all_scribe_details[] = $reg_no . ' (Scribe not found)';
                                }
                            }
                        }
                    }
                    // Combine details into a single string
                    $merged_scribes = !empty($all_scribe_details)
                        ? implode(', ', $all_scribe_details)
                        : 'No'; // Fallback if no data for the specific session
                } else {
                    $merged_scribes = 'No'; // Fallback if no data is available
                }
                // Decode the JSON for remarks
                $omr_remarks = json_decode($orm_remarks->omr_remarks, true); // Convert to associative array
                // Determine session (FN/AN)
                $remarks_data = [];
                if (isset($omr_remarks['FN'])) {
                    $session = 'FN';
                    $remarks_data = $omr_remarks['FN']; // Use FN session data
                } elseif (isset($omr_remarks['AN'])) {
                    $session = 'AN';
                    $remarks_data = $omr_remarks['AN']; // Use AN session data
                }
                // Initialize arrays for different remark types
                $blank_omr_numbers = [];
                $pencil_omr_numbers = [];
                $pen_other_than_black_numbers = [];
                $non_personalized_omr_numbers = []; // New for "Used Non-Personalized OMR"
                // Filter the remarks data
                foreach ($remarks_data as $remark) {
                    if ($remark['remark'] === 'Returned Blank OMR Sheet') {
                        $blank_omr_numbers[] = $remark['registration_number'];
                    } elseif ($remark['remark'] === 'Used Pencil in OMR Sheet') {
                        $pencil_omr_numbers[] = $remark['registration_number'];
                    } elseif ($remark['remark'] === 'Used Other Than Black Ballpoint Pen') {
                        $pen_other_than_black_numbers[] = $remark['registration_number'];
                    } elseif ($remark['remark'] === 'Used Non-Personalized OMR') { // New condition
                        $non_personalized_omr_numbers[] = $remark['registration_number'];
                    }
                }

                // Prepare comma-separated strings
                $formatted_remarks_string = implode(', ', array_column($remarks_data, 'registration_number'));
                $blank_omr = !empty($blank_omr_numbers) ? implode(', ', $blank_omr_numbers) : 'No';
                $pencil_omr = !empty($pencil_omr_numbers) ? implode(', ', $pencil_omr_numbers) : 'No';
                $pen_other_than_black = !empty($pen_other_than_black_numbers) ? implode(', ', $pen_other_than_black_numbers) : 'No';
                $non_personalized_omr = !empty($non_personalized_omr_numbers) ? implode(', ', $non_personalized_omr_numbers) : 'No';

                // Pass data to the view or debug with dd()
                $remarks_summary = [
                    // 'formatted_remarks_string' => implode(', ', array_column($remarks_data, 'registration_number')),
                    'blank_omr' => !empty($blank_omr_numbers) ? implode(', ', $blank_omr_numbers) : 'No',
                    'pencil_omr' => !empty($pencil_omr_numbers) ? implode(', ', $pencil_omr_numbers) : 'No',
                    'pen_other_than_black' => !empty($pen_other_than_black_numbers) ? implode(', ', $pen_other_than_black_numbers) : 'No',
                    'non_personalized_omr' => !empty($non_personalized_omr_numbers) ? implode(', ', $non_personalized_omr_numbers) : 'No',
                ];
                // Decode the JSON for candidate remarks
                $candidate_remarks = json_decode($orm_remarks->candidate_remarks, true); // Convert to associative array

                // Initialize arrays for different types of candidate remarks
                $malpractice_registration_numbers = [];
                $wrong_seating_registration_numbers = [];
                $wrong_omr_registration_numbers = [];
                $left_exam_registration_numbers = [];

                // Initialize session type (could be passed from the controller or from the front end)
                $session = $exam_session_type;  // For example, FN or AN

                // Initialize the array to store remarks data
                $remarks_data = isset($candidate_remarks[$session]) ? $candidate_remarks[$session] : [];

                // Filter the remarks data based on types of remarks
                foreach ($remarks_data as $remark) {
                    if ($remark['remark'] === 'Indulged in Malpractice') {
                        $malpractice_registration_numbers[] = $remark['registration_number'];
                    } elseif ($remark['remark'] === 'Wrongly Seated') {
                        $wrong_seating_registration_numbers[] = $remark['registration_number'];
                    } elseif ($remark['remark'] === 'Used OMR of Another Candidate') {
                        $wrong_omr_registration_numbers[] = $remark['registration_number'];
                    } elseif ($remark['remark'] === 'Left Exam During Examination') {
                        $left_exam_registration_numbers[] = $remark['registration_number'];
                    }
                }

                // Prepare comma-separated strings
                $malpractice = !empty($malpractice_registration_numbers) ? implode(', ', $malpractice_registration_numbers) : 'No';
                $wrong_seating = !empty($wrong_seating_registration_numbers) ? implode(', ', $wrong_seating_registration_numbers) : 'No';
                $wrong_omr = !empty($wrong_omr_registration_numbers) ? implode(', ', $wrong_omr_registration_numbers) : 'No';
                $left_exam = !empty($left_exam_registration_numbers) ? implode(', ', $left_exam_registration_numbers) : 'No';

                // Prepare summary data for the view
                $candidate_remarks_summary = [
                    'malpractice' => $malpractice,
                    'wrong_seating' => $wrong_seating,
                    'wrong_omr' => $wrong_omr,
                    'left_exam' => $left_exam,
                ];
                // Decode the JSON for remarks
                $qp_timing_log = json_decode($qp_box_timing->qp_timing_log, true);
                // Determine the session type ('FN' or 'AN') based on your requirement
                $qp_box_open_time = null;

                // Loop through the qp_timing_log array to find the matching session and retrieve qp_box_open_time
                foreach ($qp_timing_log as $log) {
                    if ($log['session'] == $session) {
                        $qp_box_open_time = \Carbon\Carbon::parse($log['qp_box_open_time'])->format('h:i A'); // AM/PM format
                        // $qp_box_open_time = \Carbon\Carbon::parse($log['qp_box_open_time'])->format('d-m-Y h:i A'); // Full date and AM/PM time
                        break;
                    }
                }
                // Pass data to the view
                // return view('PDF.Reports.ci-consolidate-report', compact('exam_data', 'exam_session_type', 'exam_date', 'user', 'hall_code', 'qp_box_open_time', 'remarks_summary', 'candidate_remarks_summary', 'merged_scribes', 'checklist_videography_data','checklist_consolidate_data'));

                $html = view('PDF.Reports.ci-consolidate-report', compact('exam_data', 'exam_session_type', 'exam_date', 'user', 'hall_code', 'qp_box_open_time', 'remarks_summary', 'candidate_remarks_summary', 'merged_scribes', 'checklist_videography_data','checklist_consolidate_data'))->render();

                // Generate the PDF using Browsershot
                $pdf = Browsershot::html($html)
                    ->setOption('landscape', false)
                    ->setOption('margin', [
                        'top' => '10mm',
                        'right' => '10mm',
                        'bottom' => '10mm',
                        'left' => '10mm'
                    ])
                    ->setOption('displayHeaderFooter', true)
                    ->setOption('headerTemplate', '<div></div>')
                    ->setOption('footerTemplate', '
            <div style="font-size:10px;width:100%;text-align:center;">
                Page <span class="pageNumber"></span> of <span class="totalPages"></span>
            </div>
            <div style="position: absolute; bottom: 5mm; right: 10px; font-size: 10px;">
                IP: ' . request()->ip() . ' | Timestamp: ' . now()->format('d-m-Y H:i:s') . '
            </div>')
                    ->setOption('preferCSSPageSize', true)
                    ->setOption('printBackground', true)
                    ->scale(1)
                    ->format('A4')
                    ->pdf();

                // Define a unique filename for the report
                $filename = 'consolidated-report-' . time() . '.pdf';

                // Return the PDF as a response
                return response($pdf)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
            }
        }
    }
}
