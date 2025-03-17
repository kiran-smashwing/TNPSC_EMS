<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessTrunkBoxQRCsv;
use App\Models\Center;
use App\Models\ExamConfirmedHalls;
use App\Models\ExamTrunkBoxOTLData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Services\ExamAuditService;
use Exception;
class ExamTrunkBoxOTLDataController extends Controller
{
    protected $auditService;

    public function __construct(ExamAuditService $auditService)
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
        $this->auditService = $auditService;
    }
    public function index($examId)
    {
        $examMaterials = ExamTrunkBoxOTLData::where('exam_id', $examId)->with(['district', 'center', 'venue'])->get();
        return view('my_exam.ExamTrunkBoxOTLData.index', compact('examMaterials', 'examId'));
    }

    public function downloadSampleCsv()
    {
        $header = ['Hall No', 'Trunk Box QR', 'OTL', 'Date'];
        $data = [
            ['001,002', '0101121', '123456,123457', '03-08-2024'],
            ['003', '0101123', '123456,34567,21345', '03-08-2024'],
            ['004', '0101124', '123456', '03-08-2024'],
            ['005', '0101125', '123456,546743', '03-08-2024'],
            ['006', '0101126', '123456,212345', '03-08-2024'],
        ];
        $callback = function () use ($header, $data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $header);

            foreach ($data as $row) {
                // Add a tab character or single quote before numbers to retain leading zeros in Excel
                $row = array_map(function ($value) {
                    return "\t" . $value; // Adding a tab to force Excel to treat as text
                }, $row);
                fputcsv($file, $row);
            }
            fclose($file);
        };

        $filename = "sample_exam_trunkbox_qr_otl_data.csv";

        return Response::stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }

    public function uploadCsv(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'exam_id' => 'required|exists:exam_main,exam_main_no',
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $examId = $request->input('exam_id');
        $file = $request->file('csv_file');
        // Read the original file content
        $uploadedFilePath = 'uploads/csv_files/';
        $uploadedFileName = 'QD_TRUNKBOX_QR_OTL' . $examId . '_uploaded_' . time() . '.csv';
        $fullFilePath = storage_path("app/public/{$uploadedFilePath}{$uploadedFileName}");

        $existingFiles = glob(storage_path("app/public/{$uploadedFilePath}{$examId}_uploaded_*"));
        foreach ($existingFiles as $existingFile) {
            unlink($existingFile); // Delete old files
        }

        // Move the file to storage
        $file->move(storage_path("app/public/{$uploadedFilePath}"), $uploadedFileName);
        $uploadedFileUrl = asset('storage/' . $uploadedFilePath . $uploadedFileName);

        // Create new file with preserved formatting
        $uploadedFilePath = 'uploads/csv_files/';
        $fullPath = storage_path('app/public/' . $uploadedFilePath . $uploadedFileName);
        // Ensure directory exists
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0777, true);
        }

        // Add tab to the first column (optional pre-processing)
        $rows = array_map('str_getcsv', file($fullFilePath));
        $fp = fopen($fullFilePath, 'w');
        foreach ($rows as $row) {
            if (!empty($row)) {
                $row[0] = "\t" . $row[0];
            }
            fputcsv($fp, $row);
        }
        fclose($fp);
        // Create initial log entry with 'processing' status
        $currentUser = current_user();
        $userName = $currentUser ? $currentUser->display_name : 'Unknown';
        $initialMetadata = [
            'user_name' => $userName,
            'status' => 'processing',
            'uploaded_csv_link' => $uploadedFileUrl,
        ];

        // Check if a log already exists for this exam and task type
        $existingLog = $this->auditService->findLog([
            'exam_id' => $examId,
            'task_type' => 'exam_trunkbox_qr_otl_upload',
        ]);

        if ($existingLog) {
            // Update the existing log
            $this->auditService->updateLog(
                logId: $existingLog->id,
                metadata: $initialMetadata,
                description: 'Updated ED Exam Trunkbox QR & OTL Code'
            );
        } else {
            // Create a new log entry
            $this->auditService->log(
                examId: $examId,
                actionType: 'uploaded',
                taskType: 'exam_trunkbox_qr_otl_upload',
                description: 'Started processing ED Exam Trunkbox QR CSV',
                metadata: $initialMetadata
            );
        }
        // Dispatch the job to process the CSV in the background
        ProcessTrunkBoxQRCsv::dispatch($examId, $fullFilePath, $uploadedFileUrl, $currentUser);

        return redirect()->back()->with('success', 'CSV file uploaded successfully. Processing will continue in the background, and you will receive an email upon completion.');
    }

}
