<?php

namespace App\Http\Controllers;

use Spatie\Browsershot\Browsershot;
use App\Models\Currentexam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Center;
use App\Models\CIChecklistAnswer;
use App\Models\ExamConfirmedHalls;
use App\Models\ExamMaterialsData;
use App\Models\ChiefInvigilator;
use App\Models\Scribe;
use Illuminate\Support\Facades\Auth;
use App\Models\District;

class ConsolidatedStatementController extends Controller
{
    public function index()
    {
        $districts = District::all(); // Fetch all districts
        // Fetch unique center values from the same table
        $centers = center::all(); // Fetch all venues
        return view('view_report.consolidated_statement_report.index', compact('districts', 'centers')); // Path matches the file created
    }
    public function getDropdownData(Request $request)
    {
        $notificationNo = $request->query('notification_no');

        // Check if notification exists
        $examDetails = Currentexam::where('exam_main_notification', $notificationNo)->first();

        if (!$examDetails) {
            return response()->json(['error' => 'Invalid Notification No'], 404);
        }

        // Retrieve the role and guard from session
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;

        // Get the user based on the guard
        $user = $guard ? $guard->user() : null;
        $districtCodeFromSession = $user ? $user->district_code : null; // Assuming district_code is in the user table
        $centerCodeFromSession = $user ? $user->center_code : null; // Assuming center_code is in the user table or retrieved through a relationship

        // Fetch districts - shown for headquarters and district roles
        $districts = [];
        if ($role === 'headquarters' || $role === 'district') {
            $districts = DB::table('district')
                ->select('district_code as id', 'district_name as name')
                ->get();
        }

        // Fetch centers
        $centers = [];
        if ($role === 'headquarters') {
            // Fetch all centers for headquarters
            $centers = DB::table('centers')
                ->select('center_code as id', 'center_name as name')
                ->get();
        } elseif ($role === 'district') {
            // Fetch centers for the specific district
            if ($districtCodeFromSession) {
                $centers = DB::table('centers')
                    ->where('center_district_id', $districtCodeFromSession)
                    ->select('center_code as id', 'center_name as name')
                    ->get();
            }
        }

        // Exam Dates and Sessions - applicable to all roles
        $examDates = DB::table('exam_session')
            ->where('exam_sess_mainid', $examDetails->exam_main_no)
            ->select('exam_sess_date')
            ->distinct()
            ->pluck('exam_sess_date');

        $sessions = DB::table('exam_session')
            ->where('exam_sess_mainid', $examDetails->exam_main_no)
            ->select('exam_sess_session')
            ->distinct()
            ->pluck('exam_sess_session');
        // Return data with logic applied based on roles
        return response()->json([
            'user' => $user,
            'districts' => $districts,
            'centers' => $centers,
            'examDates' => $examDates,
            'sessions' => $sessions,
            'centerCodeFromSession' => $centerCodeFromSession,
        ]);
    }

    public function filterConsolidatedStatement(Request $request)
    {
        $query = Currentexam::with('examservice');

        // Filter by Notification No
        if ($request->filled('notification_no')) {
            $query->where('exam_main_notification', $request->notification_no);
        }

        // Fetch the filtered results
        $exam_data = $query->get();

        // Ensure there's at least one record before accessing properties
        if ($exam_data->isNotEmpty()) {
            $exam_main_no = $exam_data->first()->exam_main_no;
        } else {
            return redirect()->back()->with([
                'error' => 'No exam data found.',
                'statement_data' => [] // Ensure empty array is passed
            ]);
        }

        // ✅ Always define `$statement_data`
        $statement_data = [];

        // Fetch Chief Invigilator details along with required fields
        $ci_data = CIChecklistAnswer::where('exam_id', $exam_main_no)
            ->with([
                'center.district', // Ensure center and district relationships exist
                'venue'            // Ensure venue relationship exists
            ])
            ->get();

        if ($ci_data->isNotEmpty()) {
            // Extract required fields
            $statement_data = $ci_data->map(function ($item) {
                // Fetch Chief Invigilator details for each `ci_id`
                $ci_data_details = ChiefInvigilator::where('ci_id', $item->ci_id)
                    ->with(['venue'])
                    ->first();

                return [
                    'district' => optional($item->center)->district->district_name ?? 'N/A',
                    'center' => optional($item->center)->center_name . ' - ' . optional($item->center)->center_code ?? 'N/A',
                    'hall_code' => $item->hall_code ?? 'N/A',
                    'venue_name' => optional($ci_data_details->venue)->venue_name ?? 'N/A',
                    'ci_id' => optional($ci_data_details)->ci_id ?? 'N/A',
                    'ci_name' => optional($ci_data_details)->ci_name ?? 'N/A',
                    'ci_phone' => optional($ci_data_details)->ci_phone ?? 'N/A',
                    'ci_email' => optional($ci_data_details)->ci_email ?? 'N/A',
                ];
            })->toArray();
        }

        // ✅ Capture the session input from the request
        $session = $request->input('session', ''); // Default empty if not provided
        $exam_date = $request->input('exam_date', '');
        return view('view_report.consolidated_statement_report.index', compact(
            'exam_data',
            'exam_main_no',
            'statement_data',
            'session', // Pass session to the Blade view
            'exam_date'
        ));
    }

    public function generateconsolidatedReport($examId, $exam_date, $exam_session , $ci_id)
    {
        // Get the role and user from the session
        // $role = session('auth_role');
        // $guard = $role ? Auth::guard($role) : null;
        $users = $ci_id;
        $user = ChiefInvigilator::where('ci_id', $users)
                    ->with(['venue'])
                    ->first();

        if (!$user) {
            abort(403, 'Unauthorized action.');
        }
        $exam_session_type = $exam_session;
        // dd($exam_date);
        // Retrieve the exam data
        $exam_data = Currentexam::with('examservice')
            ->where('exam_main_no', $examId)
            ->first();
        //   dd($exam_data); 
        if (!$exam_data) {
            abort(404, 'Exam data not found.');
        }
        $hall_code = DB::table('exam_confirmed_halls')
            ->where('exam_id', $examId)
            ->where('district_code', $user->ci_district_id)
            ->where('center_code', $user->ci_center_id)
            // ->where('venue_code', $user->ci_venue_id)
            ->where('ci_id', $user->ci_id)
            ->pluck('hall_code')
            ->first();
            // dd($hall_code);
        //CI-Qp box Log to get this all data
        $qp_box_timing = DB::table('ci_qp_box_log')
            ->where('exam_id', $examId)
            ->where('center_code', $user->ci_center_id)
            ->where('ci_id', $user->ci_id)
            ->where('hall_code', $hall_code)
            ->where('exam_date', $exam_date)
            ->first();
            // dd($qp_box_timing);
        // Get the session-specific exam material based on the session type (FN or AN)
        $examTime = ExamMaterialsData::where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->whereIn('category', ['D1', 'D2'])
            ->whereDate('exam_date', $exam_date)
            ->where('exam_session', $exam_session_type) // Match based on FN or AN session
            ->first(); // Get the first record
            // dd($examTime);
        // Initialize variables for parsed data
        $examData = ExamMaterialsData::where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->whereIn('category', ['D1', 'D2'])
            ->whereDate('exam_date', $exam_date)
            ->where('exam_session', $exam_session_type) // Match based on FN or AN session
            ->get(); // Fetch all matching records
        // dd($examData);
        // Check if the material and scan time exist
        if ($examTime && $examTime->examMaterialsScan && $examTime->examMaterialsScan->ci_scanned_at) {
            // Get the raw scan time from the database
            $rawScanTime = $examTime->examMaterialsScan->ci_scanned_at;

            // Output the raw scan time for debugging
            // dd($rawScanTime);

            // Parse and format the scan time to AM/PM format
            $scanTime = \Carbon\Carbon::parse($rawScanTime)->format('h:i A');
        } else {
            $scanTime = null; // If no scan time, set to null
        }

        // Output the formatted scan time for debugging
        // dd($scanTime); // Check the formatted time
        // end of the scantime
        $categoryLabels = [
            'I1' => 'Bundle A1',
            'I2' => 'Bundle A2',
            'R1' => 'Bundle A',
            'I3' => 'Bundle B1',
            'I4' => 'Bundle B2',
            'I5' => 'Bundle B3',
            'I6' => 'Bundle B4',
            'I7' => 'Bundle B5',
            'R2' => 'Bundle B',
            'R3' => 'Bundle I',
            'R4' => 'Bundle II',
            'R5' => 'Bundle C',
        ];

        // Query data based on the user's role
        $query = ExamMaterialsData::where('exam_id', $examId)
            ->when(function ($query) use ($user, $categoryLabels, $exam_date, $exam_session) {
                return $query->where('ci_id', $user->ci_id)
                    ->whereIn('category', array_keys($categoryLabels))
                    ->whereDate('exam_date', $exam_date)
                    ->where('exam_session', $exam_session);
            })
            ->when( function ($query) use ($categoryLabels) {
                return $query->whereIn('category', array_keys($categoryLabels));
            });

        // Fetch the results
        $examMaterials = $query->get();
        
        // Prepare the categories to be displayed in the PDF
        $pdfData = [];
        // dd($pdfData);
        // Loop through the exam materials to format the category and scan time
        foreach ($examMaterials as $examTime) {
            // Get the category name based on the category key
            $categoryName = $categoryLabels[$examTime->category] ?? 'Unknown Category';

            // Check if scan time exists and format it
            if ($examTime->examMaterialsScan && $examTime->examMaterialsScan->ci_scanned_at) {
                // Format time with AM/PM
                $scanTimes = \Carbon\Carbon::parse($examTime->examMaterialsScan->ci_scanned_at)->format('h:i A');

                // Add formatted data to pdfData array
                $pdfData[] = [
                    'category' => $categoryName,
                    'scan_time' => $scanTimes,
                ];
            }
        }

        // Combine the category and scan time into a string with commas
        $finalString = implode(', ', array_map(function ($item) {
            return $item['category'] . ' - ' . $item['scan_time'];
        }, $pdfData));

        // dd($finalString); // This will dump the combined string for debugging

        $orm_remarks = DB::table('ci_candidate_logs')
            ->where('exam_id', $examId)
            ->where('center_code', $user->ci_center_id)
            ->where('ci_id', $user->ci_id)
            ->where('hall_code', $hall_code)
            ->where('exam_date', $exam_date)
            ->first();
        //  dd($orm_remarks);
        $ci_checklist_answer = DB::table('ci_checklist_answers')
            ->where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->where('center_code', $user->ci_center_id)
            ->where('hall_code', $hall_code)
            ->first();
        //  dd($ci_checklist_answer);
        $scribe_allocation = DB::table('ci_staff_allocation')
            ->where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->where('exam_date', $exam_date)
            ->first();
        $session_confirmedhalls = ExamConfirmedHalls::where('exam_id', $examId)
            ->where('exam_session', $exam_session_type)
            ->where('exam_date', $exam_date)
            ->where('ci_id', $user->ci_id)
            ->pluck('alloted_count')
            ->first();
        //   dd($session_confirmedhalls);
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

                    foreach ($scribes_data as $session_data) {
                        // Check if the session matches the current $exam_session_type
                        $current_session = $session_data['session'];

                        if ($current_session !== $session) {
                            continue; // Skip this session if it doesn't match
                        }

                        if (isset($session_data['data'])) {
                            foreach ($session_data['data'] as $scribe_entry) {
                                $reg_no = $scribe_entry['reg_no'];
                                $scribe_id = $scribe_entry['scribe'];

                                $scribe_details = Scribe::find($scribe_id);

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

                // dd($merged_scribes);

                // Decode the JSON for remarks
                $omr_remarks = json_decode($orm_remarks->omr_remarks, true); // Convert to associative array
                $candidate_attendance = json_decode($orm_remarks->candidate_attendance, true); // Convert to associative array
                $additional_details = json_decode($orm_remarks->additional_details, true); // Convert to associative array
                //   dd($addtitional_details);
                $sessionDetails = [
                    'selectedSession' => 'No Matching Session',
                    'detailsData' => [],
                    'count' => 0,
                ];
                // Check if the session type matches and extract the data
                if (isset($additional_details['FN']) && $exam_session_type === 'FN') {
                    $sessionDetails = [
                        'selectedSession' => 'FN',
                        'detailsData' => $additional_details['FN'],
                        'count' => count($additional_details['FN']), // Count the number of candidates in FN
                    ];
                    // Dump sessionDetails after FN check

                } elseif (isset($additional_details['AN']) && $exam_session_type === 'AN') {
                    $sessionDetails = [
                        'selectedSession' => 'AN',
                        'detailsData' => $additional_details['AN'],
                        'count' => count($additional_details['AN']), // Count the number of candidates in AN
                    ];
                    // Dump sessionDetails after AN check

                }
                // Output the session details for debugging
                //    dd($sessionDetails);
                if (isset($candidate_attendance['FN']) && $exam_session_type === 'FN') {
                    $selectedSession = 'FN';
                    $attendanceData = $candidate_attendance['FN'];
                } elseif (isset($candidate_attendance['AN']) && $exam_session_type === 'AN') {
                    $selectedSession = 'AN';
                    $attendanceData = $candidate_attendance['AN'];
                } else {
                    $selectedSession = 'No Matching Session';
                    $attendanceData = [
                        'absent' => 0,
                        'present' => 0,
                    ];
                }
                // Initialize the copies array for FN and AN
                $copies = [
                    'FN' => null,
                    'AN' => null
                ];

                $copies = [];
                // Loop through exam data to parse QR codes and categorize copies
                foreach ($examData as $data) {
                    // Parse the QR code
                    $parsedQr = $this->parseQrCode($data->qr_code);

                    // Check the session type and category
                    if ($parsedQr) {
                        $session = ($parsedQr['exam_session'] === 'FN') ? 'FN' : 'AN'; // Map session to FN/AN
                        $category = $parsedQr['category'];

                        // Collect copies based on session and category
                        if (!isset($copies[$session])) {
                            $copies[$session] = ['D1' => 0, 'D2' => 0];
                        }
                        if ($category === 'D1') {
                            $copies[$session]['D1'] += $parsedQr['copies'] ?? 0;
                        } elseif ($category === 'D2') {
                            $copies[$session]['D2'] += $parsedQr['copies'] ?? 0;
                        }
                    }
                }
                // dd($copies);
                // Determine the session dynamically (FN or AN)
                $sessionType = $exam_session_type; // Default to FN if not provided

                // Attendance data
                $present = $attendanceData['present'] ?? 0; // Number of attendees present

                // Initialize debug results
                $debugResults = [
                    'session_type' => $sessionType,
                    'present' => $present,
                ];

                // Handle logic for both D1 and D2 for the session
                $totalReceivedQuestionPapers = $copies[$sessionType]['D1'] ?? 0; // D1 for Question Papers
                $totalReceivedOMR = $copies[$sessionType]['D2'] ?? 0;            // D2 for OMR Sheets
                $balanceUnusedQuestionPapers = max(0, $totalReceivedQuestionPapers - $present); // Prevent negative result
                $balanceUnusedOMR = max(0, $totalReceivedOMR - $present);                      // Prevent negative result
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
                // return view('PDF.Reports.ci-consolidate-report', compact('balanceUnusedOMR', 'balanceUnusedQuestionPapers', 'copies', 'exam_data', 'exam_session_type', 'exam_date', 'user', 'hall_code', 'qp_box_open_time', 'remarks_summary', 'candidate_remarks_summary', 'merged_scribes', 'checklist_videography_data', 'checklist_consolidate_data', 'session_confirmedhalls', 'sessionDetails', 'scanTime', 'attendanceData', 'finalString'));

                $html = view('view_report.consolidated_statement_report.ci-consolidate-statment-report', compact('balanceUnusedOMR', 'balanceUnusedQuestionPapers', 'copies', 'exam_data', 'exam_session_type', 'exam_date', 'user', 'hall_code', 'qp_box_open_time', 'remarks_summary', 'candidate_remarks_summary', 'merged_scribes', 'checklist_videography_data', 'checklist_consolidate_data', 'session_confirmedhalls', 'sessionDetails', 'scanTime', 'attendanceData', 'finalString'))->render();

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
    private function parseQrCode($qrCodeString)
    {
        // Define patterns for all QR code categories
        $patterns = [
            'D1' => '/^D1(?<notification_no>\d{6})(?<day>\d{2})(?<session>[FA])(?<center_code>\d{4})(?<venue_code>\d{3})(?<copies>\d{3})(?<box_no>\d{1})OF(?<total_boxes>\d{1})$/',
            'D2' => '/^D2(?<notification_no>\d{6})(?<day>\d{2})(?<session>[FA])(?<center_code>\d{4})(?<venue_code>\d{3})(?<copies>\d{3})$/',
            'I1' => '/^I1(?<notification_no>\d{6})(?<day>\d{2})(?<session>[FA])(?<center_code>\d{4})(?<venue_code>\d{3})$/',
            'I2' => '/^I2(?<notification_no>\d{6})(?<day>\d{2})(?<session>[FA])(?<center_code>\d{4})(?<venue_code>\d{3})$/',
            'R1' => '/^R1(?<notification_no>\d{6})(?<day>\d{2})(?<session>[FA])(?<center_code>\d{4})(?<venue_code>\d{3})$/',
            'I3' => '/^I3(?<notification_no>\d{6})(?<day>\d{2})(?<session>[FA])(?<center_code>\d{4})(?<venue_code>\d{3})$/',
            'I4' => '/^I4(?<notification_no>\d{6})(?<day>\d{2})(?<session>[FA])(?<center_code>\d{4})(?<venue_code>\d{3})$/',
            'I5' => '/^I5(?<notification_no>\d{6})(?<day>\d{2})(?<session>[FA])(?<center_code>\d{4})(?<venue_code>\d{3})$/',
            'I6' => '/^I6(?<notification_no>\d{6})(?<day>\d{2})(?<session>[FA])(?<center_code>\d{4})(?<venue_code>\d{3})$/',
            'I7' => '/^I7(?<notification_no>\d{6})(?<day>\d{2})(?<session>[FA])(?<center_code>\d{4})(?<venue_code>\d{3})$/',
            'R2' => '/^R2(?<notification_no>\d{6})(?<day>\d{2})(?<session>[FA])(?<center_code>\d{4})(?<venue_code>\d{3})$/',
            'R3' => '/^R3(?<notification_no>\d{6})(?<day>\d{2})(?<session>[FA])(?<center_code>\d{4})(?<venue_code>\d{3})$/',
            'R4' => '/^R4(?<notification_no>\d{6})(?<day>\d{2})(?<session>[FA])(?<center_code>\d{4})(?<venue_code>\d{3})$/',
            'R5' => '/^R5(?<notification_no>\d{6})(?<day>\d{2})(?<session>[FA])(?<center_code>\d{4})(?<venue_code>\d{3})$/',
        ];

        // Iterate through each pattern to match the QR code
        foreach ($patterns as $category => $pattern) {
            if (preg_match($pattern, $qrCodeString, $matches)) {
                // Return parsed details
                return [
                    'category' => $category,                        // Category identifier (e.g., D1, D2)
                    'notification_no' => $matches['notification_no'], // Notification number
                    'exam_date' => $matches['day'],                 // Day or exam date
                    'exam_session' => ($matches['session'] === 'F') ? 'FN' : 'AN', // Session (Forenoon/Afternoon)
                    'center_code' => $matches['center_code'],       // Center code
                    'hall_code' => $matches['venue_code'] ?? null,  // Venue code
                    'copies' => (int)($matches['copies'] ?? 0),     // Copies
                    'box_no' => $matches['box_no'] ?? null,         // Box number (if applicable)
                    'total_boxes' => $matches['total_boxes'] ?? null, // Total boxes (if applicable)
                ];
            }
        }

        // Return null if no pattern matched
        return null;
    }
}
