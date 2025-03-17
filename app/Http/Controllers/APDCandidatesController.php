<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessCandidatesCsv;
use App\Jobs\ProcessFinalizeHallsCsv;
use Illuminate\Http\Request;
use App\Services\ExamAuditService;

class APDCandidatesController extends Controller
{
    protected $auditService;

    public function __construct(ExamAuditService $auditService)
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
        $this->auditService = $auditService;
    }

    public function downloadSampleCsv(Request $request)
    {
        $data = [
            ['center_code', 'date', 'session', 'count'],
            ['0102', '01-01-2024', 'FN', 100],
            ['0102', '01-01-2024', 'AN', 100],
            ['0201', '01-01-2024', 'FN', 100],
            ['0201', '01-01-2024', 'AN', 100],
            ['2704', '01-01-2024', 'FN', 100],
            ['2704', '01-01-2024', 'AN', 100],
        ];

        $filename = "apd_candidates_" . date('Ymd') . ".csv";

        // Create CSV data in memory
        $handle = fopen('php://output', 'w');
        ob_start(); // Start output buffering
        fputcsv($handle, $data[0]); // Add headers to the CSV
        foreach (array_slice($data, 1) as $row) {
            // Add tab prefix to each cell in the row
            $tabbedRow = array_map(function ($cell) {
                return "\t" . $cell;
            }, $row);
            fputcsv($handle, $tabbedRow); // Add data rows to the CSV with tab prefix
        }
        fclose($handle);
        $csvOutput = ob_get_clean(); // Capture CSV output

        // Return CSV response
        return response($csvOutput, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
    public function uploadCandidatesCsv(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|integer',
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $examId = $request->input('exam_id');
        $file = $request->file('csv_file');
        // Save the uploaded file
        $uploadedFilePath = 'uploads/csv_files/';
        $uploadedFileName = $examId . '_uploaded_' . time() . '.csv';
        $fullFilePath = storage_path("app/public/{$uploadedFilePath}{$uploadedFileName}");
        // Check for duplicate files and remove them
        $existingFiles = glob(storage_path("app/public/{$uploadedFilePath}{$examId}_uploaded_*"));
        foreach ($existingFiles as $existingFile) {
            unlink($existingFile); // Delete old files
        }

        // Move the file to storage
        $file->move(storage_path("app/public/{$uploadedFilePath}"), $uploadedFileName);
        $uploadedFileUrl = asset('storage/' . $uploadedFilePath . $uploadedFileName);

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
            'task_type' => 'apd_expected_candidates_upload',
        ]);

        if ($existingLog) {
            // Update the existing log
            $this->auditService->updateLog(
                logId: $existingLog->id,
                metadata: $initialMetadata,
                description: 'Updated APD expected candidates CSV log'
            );
        } else {
            // Create a new log entry
            $this->auditService->log(
                examId: $examId,
                actionType: 'uploaded',
                taskType: 'apd_expected_candidates_upload',
                description: 'Started processing APD expected candidates CSV',
                metadata: $initialMetadata
            );
        }
        // Dispatch the job to process the CSV in the background
        ProcessCandidatesCsv::dispatch($examId, $fullFilePath, $uploadedFileUrl, $currentUser);

        return redirect()->back()->with('success', 'CSV file uploaded successfully. Processing will continue in the background, and you will receive an email upon completion.');

    }

    /**
     * @param string $filename
     * @param array $data
     *     // Download the Finalize Halls sample CSV file
     * */
    public function downloadFinalizeHallsSampleCsv(Request $request)
    {
        //center_code	hall_code	exam_date	exam_session	canidates_count

        $data = [
            ['center_code', 'hall_code', 'exam_date', 'exam_session', 'canidates_count'],
            ['0102', '001', '01-01-2024', 'FN', 100],
            ['0102', '002', '01-01-2024', 'AN', 200],
            ['0103', '001', '01-01-2024', 'FN', 150],
            ['0103', '002', '01-01-2024', 'AN', 250],
            ['0104', '001', '01-01-2024', 'FN', 200],
            ['0104', '002', '01-01-2024', 'AN', 300],
        ];

        $filename = "apd_candidates_" . date('Ymd') . ".csv";

        // Create CSV data in memory
        $handle = fopen('php://output', 'w');
        ob_start(); // Start output buffering
        fputcsv($handle, $data[0]); // Add headers to the CSV
        foreach (array_slice($data, 1) as $row) {
            // Add tab space and zero-padding while keeping them as plain text
            $row[0] = "\t" . str_pad(trim($row[0]), 4, '0', STR_PAD_LEFT); // Center Code (min 4 digits)
            $row[1] = "\t" . str_pad(trim($row[1]), 3, '0', STR_PAD_LEFT); // Hall Code (min 3 digits)

            fputcsv($handle, $row); // Add data rows to the CSV
        }
        fclose($handle);
        $csvOutput = ob_get_clean(); // Capture CSV output

        // Return CSV response
        return response($csvOutput, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
    /**
     * Finalize the halls confirmed by ID 
     */
    public function finalizeHalls(Request $request)
    {
        // Validate request data
        $request->validate([
            'exam_id' => 'required|integer',
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $examId = $request->input('exam_id');
        $file = $request->file('csv_file');

        // Save the uploaded file
        $uploadedFilePath = 'uploads/csv_files/';
        $uploadedFileName = 'APD_FINALIZE_HALLS_' . $examId . '_uploaded_' . time() . '.csv';
        $fullFilePath = storage_path("app/public/{$uploadedFilePath}{$uploadedFileName}");
        // Check for duplicate files and remove them
        $existingFiles = glob(storage_path("app/public/{$uploadedFilePath}{$examId}_uploaded_*"));
        foreach ($existingFiles as $existingFile) {
            unlink($existingFile); // Delete old files
        }

        // Move the file to storage
        $file->move(storage_path("app/public/{$uploadedFilePath}"), $uploadedFileName);
        $uploadedFileUrl = asset('storage/' . $uploadedFilePath . $uploadedFileName);

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
            'task_type' => 'apd_finalize_halls_upload',
        ]);

        if ($existingLog) {
            // Update the existing log
            $this->auditService->updateLog(
                logId: $existingLog->id,
                metadata: $initialMetadata,
                description: 'Updated APD expected candidates CSV log'
            );
        } else {
            // Create a new log entry
            $this->auditService->log(
                examId: $examId,
                actionType: 'uploaded',
                taskType: 'apd_finalize_halls_upload',
                description: 'Started processing APD expected candidates CSV',
                metadata: $initialMetadata
            );
        }
        // Dispatch the job to process the CSV in the background
        ProcessFinalizeHallsCsv::dispatch($examId, $fullFilePath, $uploadedFileUrl, $currentUser);

        return redirect()->back()->with('success', 'CSV file uploaded successfully. Processing will continue in the background, and you will receive an email upon completion.');
    }

}