<?php

namespace App\Jobs;

use App\Mail\CsvProcessingResult;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Models\Currentexam;
use App\Models\Center;
use Carbon\Carbon;
use App\Services\ExamAuditService;
use Mail;

class ProcessCandidatesCsv implements ShouldQueue
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

            $table = 'exam_candidates_projection';
            $currentTime = now();

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $totalRows++;
                try {
                    if (empty(array_filter($data))) {
                        continue;
                    }
                    $this->validateAndInsertRow($data, $this->examId, $table, $currentTime, $exam, $successfulInserts, $failedRows);
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

        $this->logUploadAction($exam, $this->examId, $successfulInserts, count($failedRows), $this->uploadedFileUrl, $failedCsvPath,$this->currentUser);
        $this->sendProcessingResultEmail($status, $successfulInserts, count($failedRows), $errorMessage, $failedCsvPath);
    }

    private function generateFailedCsv($failedRows)
    {
        $failedCsvPath = 'failes_csv/' . $this->examId . '_failed_rows_' . time() . '.csv';
        $filePath = storage_path('app/public/' . $failedCsvPath);

        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0777, true);
        }

        $fp = fopen($filePath, 'w');
        fputcsv($fp, ['Center Code', 'Center Name', 'Exam Date', 'Session', 'Expected Candidates', 'Error']);

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
            'taskType' => 'Expected Candidates',
            'status' => $status,
            'successfulInserts' => $successfulInserts,
            'failedCount' => $failedCount,
            'errorMessage' => $errorMessage,
            'uploadedCsvLink' => $this->uploadedFileUrl,
            'failedCsvLink' => $failedCsvPath ? asset('storage/' . $failedCsvPath) : null,
        ];

        try {
            Mail::to($this->currentUser->dept_off_email)->send(new CsvProcessingResult($data, $subject));
            // Mail::to('kiran@smashwing.com')->send(new CsvProcessingResult($data, $subject));
        } catch (\Exception $e) {
            \Log::error("Failed to send email: " . $e->getMessage());
        }
    }
    private function validateAndInsertRow($data, $examId, $table, $currentTime, $exam, &$successfulInserts, &$failedRows)
    {
        $centerCode = isset($data[0]) ? trim($data[0]) : '';
        if (!is_numeric($centerCode)) {
            throw new \Exception('Invalid or missing center code.');
        }
        $centerCode = str_pad((string) $centerCode, 4, '0', STR_PAD_LEFT);

        $examDate = isset($data[1]) ? trim($data[1]) : '';
        $session = isset($data[2]) ? trim($data[2]) : '';
        $expectedCandidates = isset($data[3]) ? trim($data[3]) : '';

        if (!$examDate || !Carbon::createFromFormat('d-m-Y', $examDate)) {
            throw new \Exception('Invalid or missing exam date.');
        }
        if (!$session || !in_array(strtoupper($session), ['FN', 'AN'])) {
            throw new \Exception('Invalid session. Must be FN or AN.');
        }
        if (!$expectedCandidates || !is_numeric($expectedCandidates)) {
            throw new \Exception('Invalid or missing expected candidates count.');
        }

        $duplicate = DB::table($table)
            ->where('exam_id', $examId)
            ->where('center_code', $centerCode)
            ->where('exam_date', Carbon::createFromFormat('d-m-Y', $examDate)->format('Y-m-d'))
            ->where('session', strtoupper($session))
            ->first();

        if ($duplicate) {
            $errorDetails = "Duplicate entry found. Existing data - Center Code: {$duplicate->center_code}, Exam Date: {$duplicate->exam_date}, Session: {$duplicate->session}, Expected Candidates: {$duplicate->expected_candidates}, Updated At: {$duplicate->updated_at}";
            throw new \Exception($errorDetails);
        }

        $center = Center::where('center_code', $centerCode)->first();
        if (!$center) {
            throw new \Exception("Center code '$centerCode' not found.");
        }

        $examSession = $exam->examsession()
            ->where('exam_sess_session', strtoupper($session))
            ->where('exam_sess_date', $examDate)
            ->first();
        if (!$examSession) {
            throw new \Exception("Exam date '$examDate' and session '$session' not found.");
        }

        $insertData = [
            'exam_id' => $examId,
            'center_code' => $centerCode,
            'district_code' => $center->district->district_code,
            'exam_date' => Carbon::createFromFormat('d-m-Y', $examDate)->format('Y-m-d'),
            'session' => strtoupper($session),
            'expected_candidates' => $expectedCandidates,
            'created_at' => $currentTime,
            'updated_at' => $currentTime,
        ];

        DB::table($table)->insert($insertData);
        $successfulInserts++;
    }
    private function deleteOldFailedFiles($examId)
    {
        $failedCsvFiles = glob(storage_path('app/public/failes_csv/' . $examId . '_failed_rows_*.csv'));

        // Loop through and delete old failed CSV files
        foreach ($failedCsvFiles as $file) {
            if (file_exists($file)) {
                unlink($file); // Delete the file
            }
        }
    }
    private function logUploadAction($exam, $examId, $successfulInserts, $failedCount, $uploadedFileUrl, $failedCsvPath,$currentUser)
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
            'task_type' => 'apd_expected_candidates_upload',
        ]);

        if ($existingLog) {
            // Update the existing log
            $this->auditService->updateLog(
                logId: $existingLog->id,
                metadata: $metadata,
                afterState: $exam->toArray(),
                description: 'Updated APD expected candidates CSV log'
            );
        } else {
            // Create a new log entry
            $this->auditService->log(
                examId: $examId,
                actionType: 'uploaded',
                taskType: 'apd_expected_candidates_upload',
                afterState: $exam->toArray(),
                description: 'Uploaded APD expected candidates CSV',
                metadata: $metadata
            );
        }
    }
}
