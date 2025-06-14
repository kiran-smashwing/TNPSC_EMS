<?php

namespace App\Jobs;

use App\Mail\CsvProcessingResult;
use App\Models\ExamConfirmedHalls;
use App\Models\ExamMaterialsData;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Currentexam;
use App\Models\Center;
use App\Services\ExamAuditService;
use Mail;

class ProcessMaterailsQRCsv implements ShouldQueue
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

        $this->logUploadAction($this->examId, $successfulInserts, count($failedRows), $this->uploadedFileUrl, $failedCsvPath, $this->currentUser);
        $this->sendProcessingResultEmail($status, $successfulInserts, count($failedRows), $errorMessage, $failedCsvPath);
    }
    private function generateFailedCsv($failedRows)
    {
        $failedCsvPath = 'failes_csv/' . $this->examId . '_qd_exam_materials_qr_failed_rows_' . time() . '.csv';
        $filePath = storage_path('app/public/' . $failedCsvPath);

        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0777, true);
        }

        $fp = fopen($filePath, 'w');
        fputcsv($fp, ['Hall Code', 'Center Code', 'QR Code', 'Error']);

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
            'taskType' => 'Exam Materials QR',
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
        $qrCodeString = isset($data[2]) ? trim($data[2]) : '';
        if (!is_string($qrCodeString)) {
            throw new \Exception('Invalid or missing qr code data.');
        }
        $parsedData = $this->parseQrCode($qrCodeString);
        if (!$parsedData) {
            $error = 'QR Code format invalid.';
            throw new \Exception($error);
        }
        // Normalize the notification number from the QR code
        $qrNotificationNo = $parsedData['notification_no']; // This is already in the "main format" (6 digits)

        // Fetch the exam details for the provided exam ID (sub-exam ID)
        $exam = Currentexam::where('exam_main_no',$examId)->first();
        if (!$exam) {
            throw new \Exception('Exam not found for the current exam ID.');
        }

        // Normalize the notification number from the database
        $dbNotificationNo = preg_replace('/[^0-9]/', '', $exam->exam_main_notification); // Remove non-numeric characters

        // Match the normalized notification numbers
        if ($qrNotificationNo !== $dbNotificationNo) {
            throw new \Exception('QR Code notification number does not match the exam notification number.');
        }
        $dayToMatch = $parsedData['exam_date'];
        $examsession = $parsedData['exam_session'] == 'A' ? 'AN' : 'FN';

        $exam = Currentexam::where('exam_main_no', $examId)
            ->with([
                'examsession' => function ($query) use ($dayToMatch, $examsession) {
                    $query->whereRaw('EXTRACT(DAY FROM exam_sess_date::date) = ?', [$dayToMatch])
                        ->where('exam_sess_session', $examsession);
                }
            ])
            ->first();
        $examMaterialsDuplicateData = ExamMaterialsData::where('exam_id', $exam->exam_main_no)
            ->where('qr_code', $data[2])
            ->first();

        if ($examMaterialsDuplicateData) {
            $errorDetails = "Duplicate Entry found. Existing data - QR Code: " . $data[2] . " - Exam ID: " . $exam->exam_main_no . " - Exam Materials Data ID: " . $examMaterialsDuplicateData;
            throw new \Exception($errorDetails);
        }

        if (!$exam) {
            $error = 'Exam not found for the given QR code data.';
            throw new \Exception($error);
        }

        $firstExamSession = $exam?->examsession->first();
        if (!$firstExamSession) {
            $error = 'Exam date or session not found for the given QR code data.';
            throw new \Exception($error);
        }

        $center = Center::where('center_code', $parsedData['center_code'])->first();
        if (!$center) {
            $error = 'Center not found for the given QR code data.';
            throw new \Exception($error);
        }
        $venueConfirmed = ExamConfirmedHalls::where('exam_id', $exam->exam_main_no)
            ->where('center_code', $parsedData['center_code'])
            ->where('hall_code', $parsedData['hall_code'])
            ->where('exam_date', $exam->examsession->first()->exam_sess_date)
            ->where('exam_session', $examsession)
            ->first();
        if (!$venueConfirmed) {
            $error = 'Hall not found for the given QR code data.';
            throw new \Exception($error);
        }

        $qrCodeEntries[] = [
            'exam_id' => $exam->exam_main_no,
            'district_code' => $center->center_district_id,
            'center_code' => $parsedData['center_code'],
            'hall_code' => $parsedData['hall_code'] ?? null,
            'venue_code' => $venueConfirmed->venue_code,
            'exam_date' => $firstExamSession->exam_sess_date,
            'exam_session' => $examsession,
            'qr_code' => $qrCodeString,
            'category' => $parsedData['category'],
            'ci_id' => $venueConfirmed->ci_id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        ExamMaterialsData::insert($qrCodeEntries);
        $successfulInserts++;
    }
    private function deleteOldFailedFiles($examId)
    {
        $failedCsvFiles = glob(storage_path('app/public/failes_csv/' . $examId . '_qd_exam_materials_qr_failed_rows_*.csv'));

        // Loop through and delete old failed CSV files
        foreach ($failedCsvFiles as $file) {
            if (file_exists($file)) {
                unlink($file); // Delete the file
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
            'R1' => [
                '/^R1(?<notification_no>\d{6})(?<day>\d{2})(?<session>[FA])(?<center_code>\d{4})(?<venue_code>\d{3})(?<box_no>\d{1})OF(?<total_boxes>\d{1})$/',
                '/^R1(?<notification_no>\d{6})(?<day>\d{2})(?<session>[FA])(?<center_code>\d{4})(?<venue_code>\d{3})$/'
            ],
            'I3' => '/^I3(?<notification_no>\d{6})(?<day>\d{2})(?<session>[FA])(?<center_code>\d{4})(?<venue_code>\d{3})$/',
            'I4' => '/^I4(?<notification_no>\d{6})(?<day>\d{2})(?<session>[FA])(?<center_code>\d{4})(?<venue_code>\d{3})$/',
            'I5' => '/^I5(?<notification_no>\d{6})(?<day>\d{2})(?<session>[FA])(?<center_code>\d{4})(?<venue_code>\d{3})$/',
            'I6' => '/^I6(?<notification_no>\d{6})(?<day>\d{2})(?<session>[FA])(?<center_code>\d{4})(?<venue_code>\d{3})$/',
            'I7' => '/^I7(?<notification_no>\d{6})(?<day>\d{2})(?<session>[FA])(?<center_code>\d{4})(?<venue_code>\d{3})$/',
            'R2' => '/^R2(?<notification_no>\d{6})(?<day>\d{2})(?<session>[FA])(?<center_code>\d{4})(?<venue_code>\d{3})$/',
            'R3' => '/^R3(?<notification_no>\d{6})(?<day>\d{2})(?<session>[FA])(?<center_code>\d{4})(?<venue_code>\d{3})$/',
            'R4' => '/^R4(?<notification_no>\d{6})(?<day>\d{2})(?<session>[FA])(?<center_code>\d{4})(?<venue_code>\d{3})$/',
            'R5' => '/^R5(?<notification_no>\d{6})(?<day>\d{2})(?<session>[FA])(?<center_code>\d{4})(?<venue_code>\d{3})$/',
            'R6' => '/^R6(?<notification_no>\d{6})(?<day>\d{2})(?<session>[FA])(?<center_code>\d{4})(?<venue_code>\d{3})$/',
        ];

        // Handle patterns with multiple formats (like R1)
        foreach ($patterns as $category => $pattern) {
            if (is_array($pattern)) {
                foreach ($pattern as $subPattern) {
                    if (preg_match($subPattern, $qrCodeString, $matches)) {
                        $result = [
                            'category' => $category,
                            'notification_no' => $matches['notification_no'],
                            'exam_date' => $matches['day'],
                            'exam_session' => $matches['session'],
                            'center_code' => $matches['center_code'],
                            'hall_code' => $matches['venue_code']
                        ];

                        if (isset($matches['box_no']) && isset($matches['total_boxes'])) {
                            $result['box_no'] = $matches['box_no'];
                            $result['total_boxes'] = $matches['total_boxes'];
                        }

                        return $result;
                    }
                }
            } else {
                if (preg_match($pattern, $qrCodeString, $matches)) {
                    $result = [
                        'category' => $category,
                        'notification_no' => $matches['notification_no'],
                        'exam_date' => $matches['day'],
                        'exam_session' => $matches['session'],
                        'center_code' => $matches['center_code'],
                        'hall_code' => $matches['venue_code']
                    ];

                    if (isset($matches['box_no']) && isset($matches['total_boxes'])) {
                        $result['box_no'] = $matches['box_no'];
                        $result['total_boxes'] = $matches['total_boxes'];
                    }

                    return $result;
                }
            }
        }
        return null; // No pattern matched
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
            'task_type' => 'qd_exam_materials_qrcode_upload',
        ]);

        if ($existingLog) {
            // Update the existing log
            $this->auditService->updateLog(
                logId: $existingLog->id,
                metadata: $metadata,
                afterState: null,
                description: 'Updated QD Exam Metarial QR Code '
            );
        } else {
            // Create a new log entry
            $this->auditService->log(
                examId: $examId,
                actionType: 'uploaded',
                taskType: 'qd_exam_materials_qrcode_upload',
                afterState: null,
                description: 'Uploaded QD Exam Metarial QR Code CSV',
                metadata: $metadata
            );
        }
    }
}
