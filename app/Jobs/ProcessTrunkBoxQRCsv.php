<?php

namespace App\Jobs;

use App\Mail\CsvProcessingResult;
use App\Models\ExamConfirmedHalls;
use App\Models\ExamMaterialsData;
use App\Models\ExamTrunkBoxOTLData;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Currentexam;
use App\Models\Center;
use App\Services\ExamAuditService;
use Mail;

class ProcessTrunkBoxQRCsv implements ShouldQueue
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
                    // Assuming Hall Code is the first column
                    $hallCodes = explode(',', $data[0]); // Split hall codes if comma-separated
                    foreach ($hallCodes as $hallCode) {
                        $newData = $data; // Copy original row
                        $newData[0] = trim($hallCode); // Replace Hall Code with individual one

                        try {
                            $this->validateAndInsertRow($newData, $this->examId, $successfulInserts, $failedRows);
                        } catch (\Exception $e) {
                            $failedRows[] = array_merge($newData, ['error' => $e->getMessage()]);
                        }
                    }
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

        $this->logUploadAction($this->examId, $successfulInserts, count($failedRows), $this->uploadedFileUrl, $failedCsvPath, $this->currentUser);
        $this->sendProcessingResultEmail($status, $successfulInserts, count($failedRows), $errorMessage, $failedCsvPath);
    }
    private function generateFailedCsv($failedRows)
    {
        $failedCsvPath = 'failes_csv/' . $this->examId . '_qd_trunkbox_qr_otl_failed_rows_' . time() . '.csv';
        $filePath = storage_path('app/public/' . $failedCsvPath);

        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0777, true);
        }

        $fp = fopen($filePath, 'w');
        fputcsv($fp, ['Hall Code', 'Trunk Box', 'OTL', 'Error']);

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
        $hallCode = isset($data[0]) ? trim($data[0]) : '';
        // Validate and format hall code
        if (!is_numeric($hallCode)) {
            throw new \Exception('Invalid or missing Hall code.');
        }
        // Format hall code to 3 digits
        $hallCode = str_pad($hallCode, 3, '0', STR_PAD_LEFT);
        $qrCodeString = isset($data[1]) ? trim($data[1]) : '';
        if (!is_string($qrCodeString)) {
            throw new \Exception('Invalid or missing Trunkbox QR code.');
        }
        $otlCode = isset($data[2]) ? trim($data[2]) : '';
        if (!is_string($otlCode)) {
            throw new \Exception('Invalid or missing OTL code.');
        }
        $examDate = isset($data[3]) ? trim($data[3]) : '';
        if (!$examDate || !Carbon::createFromFormat('d-m-Y', $examDate)) {
            throw new \Exception('Invalid or missing Date.');
        }
        $parsedData = $this->parseQrCode($qrCodeString);
        if (!$parsedData) {
            $error = 'QR Code format invalid.';
            throw new \Exception($error);
        }
        $centerCode = $parsedData['center_code'];
        // Split OTL codes by comma and encode as JSON 
        $otlCodes = explode(',', trim($otlCode));
        $otlCodes = json_encode($otlCodes);
        $examTrunkBoxOTLDuplicateData = ExamTrunkBoxOTLData::where('exam_id', $examId)
            ->where('trunkbox_qr_code', trim($qrCodeString))
            ->where('center_code', $centerCode)
            ->where('hall_code', trim($hallCode))
            ->where('otl_code', $otlCodes)
            ->first();

        if ($examTrunkBoxOTLDuplicateData) {
            $errorDetails = "Duplicate Entry found. Existing data - Trunk BOX QR Code: " . $qrCodeString . " - Exam ID: " . $examId . " - Exam TRUNK BOX OTL Data ID: " . $examTrunkBoxOTLDuplicateData;
            throw new \Exception($errorDetails);
        }
        $center = Center::where('center_code', $parsedData['center_code'])->first();
        if (!$center) {
            $error = 'Center not found for the given QR code data.';
            throw new \Exception($error);
        }
        $examDate = date('Y-m-d', strtotime(trim($examDate)));
        $hallConfirmed = ExamConfirmedHalls::where('exam_id', $examId)
            ->where('center_code', $parsedData['center_code'])
            ->where('hall_code', trim($hallCode))
            ->where('exam_date', $examDate)
            ->first();

        if (!$hallConfirmed) {
            $error = 'Hall not found for the given QR code data.';
            throw new \Exception($error);
        }

        $qrCodeEntries[] = [
            'exam_id' => $examId,
            'district_code' => $center->center_district_id,
            'center_code' => $parsedData['center_code'],
            'hall_code' => $hallConfirmed->hall_code,
            'venue_code' => $hallConfirmed->venue_code,
            'exam_date' => $hallConfirmed->exam_date,
            'trunkbox_qr_code' => trim($qrCodeString),
            'otl_code' => $otlCodes,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        ExamTrunkBoxOTLData::insert($qrCodeEntries);
        $successfulInserts++;
    }
    private function deleteOldFailedFiles($examId)
    {
        $failedCsvFiles = glob(storage_path('app/public/failes_csv/' . $examId . '_qd_trunkbox_qr_otl_failed_rows_*.csv'));

        // Loop through and delete old failed CSV files
        foreach ($failedCsvFiles as $file) {
            if (file_exists($file)) {
                unlink($file); // Delete the file
            }
        }
    }

    private function parseQrCode($qrCodeString)
    {
        // Define the pattern for the Trunk Box QR code
        $pattern = '/^(?<center_code>\d{4})(?<trunk_no>\d{3})$/';

        // Match the pattern against the QR code string
        if (preg_match($pattern, $qrCodeString, $matches)) {
            return [
                'center_code' => $matches['center_code'], // 4-digit center code
                'trunk_no' => $matches['trunk_no'], // 3-digit trunk number
            ];
        }

        return null; // No match found
    }

    private function logUploadAction($examId, $successfulInserts, $failedCount, $uploadedFileUrl, $failedCsvPath, $currentUser)
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
            'task_type' => 'exam_trunkbox_qr_otl_upload',
        ]);

        if ($existingLog) {
            // Update the existing log
            $this->auditService->updateLog(
                logId: $existingLog->id,
                metadata: $metadata,
                afterState: null,
                description: 'Updated QD Trunk Box QR Code '
            );
        } else {
            // Create a new log entry
            $this->auditService->log(
                examId: $examId,
                actionType: 'uploaded',
                taskType: 'exam_trunkbox_qr_otl_upload',
                afterState: null,
                description: 'Uploaded QD Trunk Box QR Code CSV',
                metadata: $metadata
            );
        }
    }
}
