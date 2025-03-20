<?php

namespace App\Jobs;

use App\Mail\CsvProcessingResult;
use App\Models\ExamConfirmedHalls;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Currentexam;
use App\Models\Center;
use Carbon\Carbon;
use App\Services\ExamAuditService;
use Mail;

class ProcessFinalizeHallsCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $examId;
    protected $filePath;
    protected $uploadedFileUrl;
    protected $auditService;
    protected $currentUser;

    /**
     * Create a new job instance.
     */
    public function __construct($examId, $filePath, $uploadedFileUrl, $currentUser)
    {
        $this->examId = $examId;
        $this->filePath = $filePath;
        $this->uploadedFileUrl = $uploadedFileUrl;
        $this->currentUser = $currentUser;
    }
    /**
     * Execute the job.
     */
    public function handle(ExamAuditService $auditService): void
    {
        $this->auditService = $auditService;

        $status = 'completed';
        $errorMessage = null;
        $totalRows = 0;
        $successfulInserts = 0;
        $failedRows = [];
        $failedCsvPath = null;

        try {
            if (($handle = fopen($this->filePath, 'r')) === false) {
                throw new \Exception("Failed to open CSV file.");
            }

            $bom = fgets($handle, 4);
            if (!str_starts_with($bom, "\xEF\xBB\xBF")) {
                rewind($handle);
            }
            fgetcsv($handle); // Skip header

            $exam = Currentexam::where('exam_main_no', $this->examId)
                ->with('examsession')
                ->first();
            if (!$exam) {
                throw new \Exception("Exam not found.");
            }

            $this->deleteOldFailedFiles($this->examId);

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $totalRows++;
                try {
                    if (empty(array_filter($data))) {
                        continue;
                    }
                    $this->validateAndInsertRow($data, $this->examId, $successfulInserts, $failedRows);
                } catch (\Exception $e) {
                    $failedRows[] = array_merge($data, ['error' => $e->getMessage()]);
                }
            }

            fclose($handle);

            if (!empty($failedRows)) {
                $failedCsvPath = $this->generateFailedCsv($failedRows);
            }

        } catch (\Exception $e) {
            $status = 'failed';
            $errorMessage = $e->getMessage();
            \Log::error("CSV Processing Failed: " . $errorMessage);
        }

        $this->logUploadAction( $this->examId, $successfulInserts, count($failedRows), $this->uploadedFileUrl, $failedCsvPath, $this->currentUser);
        $this->sendProcessingResultEmail($status, $successfulInserts, count($failedRows), $errorMessage, $failedCsvPath);
    }

    private function generateFailedCsv($failedRows)
    {
        $failedCsvPath = 'failes_csv/' . $this->examId . '_finalizehalls_failed_rows_' . time() . '.csv';
        $filePath = storage_path('app/public/' . $failedCsvPath);

        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0777, true);
        }

        $fp = fopen($filePath, 'w');
        fprintf($fp, chr(0xEF) . chr(0xBB) . chr(0xBF)); // Add UTF-8 BOM for Excel compatibility
        fputcsv($fp, ['Center Code', 'Hall Code', 'Exam Date', 'Exam Session', 'Candidates Count', 'Error']);

        foreach ($failedRows as $row) {
            $tabbedRow = array_map(fn($cell) => "\t" . $cell, $row);
            fputcsv($fp, $tabbedRow);
        }

        fclose($fp);
        return $failedCsvPath;
    }
    private function sendProcessingResultEmail($status, $successfulInserts, $failedCount, $errorMessage, $failedCsvPath)
    {
        $subject = $status === 'completed' ? 'CSV Processing Completed' : 'CSV Processing Failed';
        $data = [
            'taskType' => 'Finalize Exam Halls',
            'status' => $status,
            'successfulInserts' => $successfulInserts,
            'failedCount' => $failedCount,
            'errorMessage' => $errorMessage,
            'uploadedCsvLink' => $this->uploadedFileUrl,
            'failedCsvLink' => $failedCsvPath ? asset('storage/' . $failedCsvPath) : null,
        ];

        try {
            // Mail::to($this->currentUser->dept_off_email)->send(new CsvProcessingResult($data, $subject));
            Mail::to('kiran@smashwing.com')->send(new CsvProcessingResult($data, $subject));
        } catch (\Exception $e) {
            \Log::error("Failed to send email: " . $e->getMessage());
        }
    }
    private function validateAndInsertRow($data, $examId, &$successfulInserts, &$failedRows)
    {
        $centerCode = isset($data[0]) ? trim($data[0]) : '';
        if (!is_numeric($centerCode)) {
            throw new \Exception('Invalid or missing center code.');
        }
        $centerCode = str_pad((string) $centerCode, 4, '0', STR_PAD_LEFT);
        $hallCode = isset($data[1]) ? trim($data[1]) : '';
        // Validate and format hall code
        if (!is_numeric($hallCode)) {
            throw new \Exception('Invalid or missing Hall code.');
        }
        // Format hall code to 3 digits
        $hallCode = str_pad($hallCode, 3, '0', STR_PAD_LEFT);
        $examDate = isset($data[2]) ? trim($data[2]) : '';
        if (!$examDate || !Carbon::createFromFormat('d-m-Y', $examDate)) {
            throw new \Exception('Invalid or missing exam date.');
        }
        $session = isset($data[3]) ? trim($data[3]) : '';
        // Validate session
        if (!$session || !in_array(strtoupper($session), ['FN', 'AN'])) {
            throw new \Exception('Invalid session. Must be FN or AN.');
        }
        $session = strtoupper($session);
        $candidatesCount = isset($data[4]) ? trim($data[4]) : '';
        // Validate candidates count
        if (!is_numeric($candidatesCount)) {
            throw new \Exception('Invalid or missing actual candidates count.');
        }


        $center = Center::where('center_code', $centerCode)->first();
        if (!$center) {
            throw new \Exception("Center code '$centerCode' not found.");
        }


        // Validate in ExamConfirmedHalls 
        $confirmedByID = ExamConfirmedHalls::where('exam_id', $examId)
            ->where('hall_code', $hallCode)
            ->where('exam_session', $session)
            ->where('exam_date', Carbon::createFromFormat('d-m-Y', $examDate)->format('Y-m-d'))
            ->where('center_code', $centerCode)
            ->first();
        if (!$confirmedByID) {
            $errorDetails = "No Confirmed Halls found. Existing data - Center Code: {$centerCode}, Hall Code: {$hallCode}, Exam Date: {$examDate}, Session: {$session}";
            throw new \Exception($errorDetails);
        }

        // Update the record
        if ($confirmedByID) {
            $confirmedByID->is_apd_uploaded = true;
            $confirmedByID->alloted_count = $candidatesCount;
            $confirmedByID->save();
        } else {
            throw new \Exception('Invalid exam model.');
        }
        $successfulInserts++;
    }
    private function deleteOldFailedFiles($examId)
    {
        $failedCsvFiles = glob(storage_path('app/public/failes_csv/' . $examId . '_finalizehalls_failed_rows_*.csv'));

        // Loop through and delete old failed CSV files
        foreach ($failedCsvFiles as $file) {
            if (file_exists($file)) {
                unlink($file); // Delete the file
            }
        }
    }
    private function logUploadAction( $examId, $successfulInserts, $failedCount, $uploadedFileUrl, $failedCsvPath, $currentUser)
    {
        $userName = $currentUser ? $currentUser->display_name : 'Unknown';

        $metadata = [
            'user_name' => $userName,
            'status' => 'completed',
            'successful_inserts' => $successfulInserts,
            'failed_count' => $failedCount,
            'uploaded_csv_link' => $uploadedFileUrl, // Include uploaded file link
            'failed_csv_link' => $failedCsvPath ? asset('storage/' . $failedCsvPath) : null,
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
                metadata: $metadata,
                afterState: null,
                description: 'Updated APD Finalize Halls CSV '
            );
        } else {
            // Create a new log entry
            $this->auditService->log(
                examId: $examId,
                actionType: 'uploaded',
                taskType: 'apd_finalize_halls_upload',
                afterState: null,
                description: 'Uploaded APD Finalize Halls CSV',
                metadata: $metadata
            );
        }
    }
}
