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
use App\Models\QpBoxLog;
use App\Models\CICandidateLogs;
use App\Models\CIStaffAllocation;
use App\Models\CIPaperReplacements;
use App\Models\CIChecklist;
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
            ->where('ci_id', $user->ci_id)
            ->where('exam_date', $exam_date)
            ->where('exam_session', $exam_session)
            ->pluck('hall_code')
            ->first();
        // dd($hall_code);
        // Get Time of Receiving Examination Material 
        $timeReceivingMaterial = ExamMaterialsData::where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->whereIn('category', ['D1', 'D2'])
            ->join('exam_materials_scans', function ($join) {
                $join->on('exam_materials_data.id', '=', 'exam_materials_scans.exam_material_id');
            })
            ->orderBy('exam_materials_scans.ci_scanned_at', 'asc') // Get the latest scanned material
            ->select('exam_materials_data.*', 'exam_materials_scans.ci_scanned_at as received_at')
            ->first(); // Get only one row
        $qpboxTimeLog = QpBoxLog::where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->where('exam_date', $exam_date)
            ->select(DB::raw("qp_timing_log->'" . $exam_session . "' as qp_timing_log"))
            ->first();
        // Fetching the remarks directly from the query
        $omrRemarksData = CICandidateLogs::where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->where('exam_date', $exam_date)
            ->select(DB::raw("omr_remarks->'$exam_session'->'remarks' as remarks"))
            ->first();

        // Initialize an empty string for registration numbers
        $nonPersonalisedOMRCandidates = '';
        $blankOMRSheetCandidates = '';
        $usedPencilCandidates = '';
        $usedOtherPenCanidates = '';

        // Check if remarks data is not empty and process it
        if (!empty($omrRemarksData->remarks)) {
            $remarksArray = json_decode($omrRemarksData->remarks, true);

            // Filter the remarks array and pluck registration numbers
            $nonPersonalisedOMRCandidates = collect($remarksArray)
                ->where('remark', 'Used Non-Personalized OMR')
                ->pluck('reg_no')
                ->implode(', ');
            // Filter the remarks array and pluck registration numbers
            $blankOMRSheetCandidates = collect($remarksArray)
                ->where('remark', 'Returned Blank OMR Sheet')
                ->pluck('reg_no')
                ->implode(', ');
            $usedPencilCandidates = collect($remarksArray)
                ->where('remark', 'Used Pencil in OMR Sheet')
                ->pluck('reg_no')
                ->implode(', ');
            $usedOtherPenCanidates = collect($remarksArray)
                ->where('remark', 'Used Other Than Black Ballpoint Pen')
                ->pluck('reg_no')
                ->implode(', ');
        }
        $selectedScribeData = CIStaffAllocation::where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->where('exam_date', $exam_date)
            ->selectRaw("scribes->'{$exam_session}' as selected_scribes")
            ->value('selected_scribes');

        // Handle case where selectedScribeData is empty or null

        // Decode JSON data and extract assignments
        $scribeAssignments = collect(json_decode($selectedScribeData, true)['scribe_assignments'] ?? []);

        // Fetch scribe details in a single query
        $scribes = Scribe::whereIn('scribe_id', $scribeAssignments->pluck('scribe_id')->unique())
            ->get()
            ->keyBy('scribe_id');

        // Format output
        $formattedScribes = $scribeAssignments->map(
            fn($a) =>
            "{$a['reg_no']} (Scribe: " .
            ($scribes[$a['scribe_id']]->scribe_name ?? 'N/A') . "/" .
            ($scribes[$a['scribe_id']]->scribe_phone ?? 'N/A') . ")"
        )->all();
        $candidateRemarksData = CICandidateLogs::where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->where('exam_date', $exam_date)
            ->select(DB::raw("candidate_remarks->'" . $exam_session . "' as remarks"))
            ->first();
        // Initialize an empty string for registration numbers
        $wronglySeatedCandidates = '';
        $usedOMRofOtherCandidates = '';
        $indulgedMalpracticeCandidates = '';
        $leftTheExamHallCandidates = '';
        // Check if remarks data is not empty and process it
        if (!empty($candidateRemarksData->remarks)) {
            $remarksArray = json_decode($candidateRemarksData->remarks, true);
            // Filter the remarks array and pluck registration numbers
            $wronglySeatedCandidates = collect($remarksArray['remarks'])
                ->where('remark', 'Wrongly Seated')
                ->pluck('registration_number')
                ->implode(', ');
            $usedOMRofOtherCandidates = collect($remarksArray['remarks'])
                ->where('remark', 'Used OMR of Another Candidate')
                ->pluck('registration_number')
                ->implode(', ');
            $indulgedMalpracticeCandidates = collect($remarksArray['remarks'])
                ->where('remark', 'Indulged in Malpractice')
                ->pluck('registration_number')
                ->implode(', ');
            $leftTheExamHallCandidates = collect($remarksArray['remarks'])
                ->where('remark', 'Left Exam During Examination')
                ->pluck('registration_number')
                ->implode(', ');
        }
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

        $lastScannedBundle = ExamMaterialsData::where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->where('exam_date', $exam_date)
            ->where('exam_session', $exam_session)
            ->whereIn('category', array_keys($categoryLabels))
            ->join('exam_materials_scans', function ($join) {
                $join->on('exam_materials_data.id', '=', 'exam_materials_scans.exam_material_id');
            })
            ->orderBy('exam_materials_scans.ci_scanned_at', 'desc')
            ->select('exam_materials_data.*', 'exam_materials_scans.ci_scanned_at as last_scanned_at')
            ->get();
        $formattedBundles = $lastScannedBundle->map(function ($item) use ($categoryLabels) {
            $categoryLabel = $categoryLabels[$item->category] ?? 'Unknown Category';
            $scannedTime = \Carbon\Carbon::parse($item->last_scanned_at)->format('g:i A');
            return "{$categoryLabel} - {$scannedTime}";
        })->all();
        $videographyAnswerData = CIChecklistAnswer::where('ci_id', $user->ci_id)
            ->where('exam_id', $examId)
            ->select(DB::raw("videography_answer->'" . $exam_date . "'->'" . $exam_session . "' as videography_answer"))
            ->first();
        $videographyAnswer = $videographyAnswerData ? $videographyAnswerData->videography_answer : null;

        $allocatedCount = ExamConfirmedHalls::where('exam_id', $examId)
            ->where('exam_session', $exam_session)
            ->where('exam_date', $exam_date)
            ->where('ci_id', $user->ci_id)
            ->pluck('alloted_count')
            ->first();

        $additionalCandidatesData = CICandidateLogs::where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->where('exam_date', $exam_date)
            ->select(DB::raw("additional_details->'" . $exam_session . "' as additional_candidates"))
            ->first();
        $totalAdditionalCandidates = 0;

        if ($additionalCandidatesData && !empty($additionalCandidatesData->additional_candidates)) {
            $additionalCandidatesArray = json_decode($additionalCandidatesData->additional_candidates, true);

            // Count the total number of additional candidates
            $totalAdditionalCandidates = count($additionalCandidatesArray);
        }
        $candidateAttendanceData = CICandidateLogs::where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->where('exam_date', $exam_date)
            ->select(DB::raw("candidate_attendance->'$exam_session' as candidate_attendance"))
            ->first();
        $candidateAttendance = $candidateAttendanceData ? $candidateAttendanceData->candidate_attendance : null;

        $totalNumberQuestionPaper = ExamMaterialsData::where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->whereIn('category', ['D1'])
            ->whereDate('exam_date', $exam_date)
            ->where('exam_session', $exam_session)
            ->get();

        $totalQPsReceived = 0;

        foreach ($totalNumberQuestionPaper as $item) {
            // Assuming the qr_code field contains the QR code data in string format
            $qrCodeData = $item->qr_code; // Example: D119201929F01000053001OF3

            // Extract the 'NO. OF COPIES' from the QR code (characters 19 to 21 are the 'NO. OF COPIES')
            $noOfCopies = substr($qrCodeData, 18, 3); // Extracts the 3 digits representing the 'NO. OF COPIES'
            // Add the number of copies to the total question papers received
            $totalQPsReceived += (int) $noOfCopies;
        }
        $totalOMRPaper = ExamMaterialsData::where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->whereIn('category', ['D2'])
            ->whereDate('exam_date', $exam_date)
            ->where('exam_session', $exam_session)
            ->get();

        $totalOMRsReceived = 0;

        foreach ($totalOMRPaper as $item) {
            // Assuming the qr_code field contains the QR code data in string format
            $qrCodeData = $item->qr_code; // Example: D119201929F01000053001OF3

            // Extract the 'NO. OF COPIES' from the QR code (characters 19 to 21 are the 'NO. OF COPIES')
            $noOfCopies = substr($qrCodeData, 18, 3); // Extracts the 3 digits representing the 'NO. OF COPIES'
            // Add the number of copies to the total question papers received
            $totalOMRsReceived += (int) $noOfCopies;
        }
        $totalPaperReplacements = CIPaperReplacements::where('exam_id', $examId)
            ->where('ci_id', $user->ci_id)
            ->where('exam_date', $exam_date)
            ->where('exam_session', $exam_session)
            ->orderBy('updated_at', 'desc') // Order by latest update
            ->count();
        $consolidateAnswer = CIChecklistAnswer::where('ci_id', $user->ci_id)
            ->where('exam_id', $examId)
            ->select(DB::raw("consolidate_answer->'" . $exam_date . "'->'" . $exam_session . "' as consolidate_answer"))
            ->first();
        $consolidateChecklist = CIChecklist::where('ci_checklist_type', 'Self Declaration')->get();
        $html = view('PDF.Reports.ci-consolidate-report', compact(
            'exam_data',
            'exam_session_type',
            'exam_date',
            'user',
            'hall_code',
            'timeReceivingMaterial',
            'qpboxTimeLog',
            'nonPersonalisedOMRCandidates',
            'blankOMRSheetCandidates',
            'usedPencilCandidates',
            'usedOtherPenCanidates',
            'formattedScribes',
            'wronglySeatedCandidates',
            'usedOMRofOtherCandidates',
            'indulgedMalpracticeCandidates',
            'leftTheExamHallCandidates',
            'formattedBundles',
            'videographyAnswer',
            'allocatedCount',
            'totalAdditionalCandidates',
            'candidateAttendance',
            'totalQPsReceived',
            'totalOMRsReceived',
            'totalPaperReplacements',
            'consolidateChecklist',
            'consolidateAnswer'
        ))->render();

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
