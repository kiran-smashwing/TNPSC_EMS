<?php

namespace App\Http\Controllers;

use App\Models\CICandidateLogs;
use App\Models\CIChecklist;
use App\Models\CIChecklistAnswer;
use App\Models\CIPaperReplacements;
use App\Models\CIStaffAllocation;
use App\Models\Scribe;
use Illuminate\Support\Facades\DB;
use App\Models\Currentexam;
use Illuminate\Support\Facades\Auth;
use App\Models\ExamConfirmedHalls;
use Illuminate\Http\Request;
use App\Models\ExamMaterialsScan;
use App\Models\ExamMaterialsData;
use Illuminate\Support\Facades\Storage;
use App\Models\QpBoxLog;
use Spatie\Browsershot\Browsershot;

class CIConsolidateController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
    }
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

        $totalAdditionalCandidates = ExamConfirmedHalls::where('exam_id', $examId)
            ->where('exam_session', $exam_session)
            ->where('exam_date', $exam_date)
            ->where('ci_id', $user->ci_id)
            ->pluck('addl_cand_count')
            ->first();
        
        // $totalAdditionalCandidates = 0;

        
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
        $examId = $examId; // Ensure you get the correct exam ID
        $examDate = \Carbon\Carbon::parse($exam_date)->format('d-m-Y'); // Format exam date
        // Define the dynamic folder path
        $folderPath = 'reports/' . $examId . '/consolidate-report/'; // Folder for the specific exam

        // Ensure the folder exists
        Storage::disk('public')->makeDirectory($folderPath);

        // Define the filename with center code, hall code, and session type
        $filename = 'consolidate-report-' . $user->center->center_code . '-' . $hall_code . '-' . $examDate . '-' . $exam_session_type . '.pdf';
        // Full file path inside the dynamically created folder
        $filePath = $folderPath . '/' . $filename;
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
        Storage::disk('public')->put($filePath, $pdf);
        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}
