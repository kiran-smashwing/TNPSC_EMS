<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\Currentexam;
use App\Models\ExamMaterialsData;
use App\Models\ExamConfirmedHalls;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Services\ExamAuditService;
use Exception;
class ExamMaterialsDataController extends Controller
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
        $examMaterials = ExamMaterialsData::where('exam_id', $examId)->with(['district','center', 'venue'])->get();
        return view('my_exam.ExamMaterialsData.index', compact('examMaterials', 'examId'));
    }

    public function downloadSampleCsv()
    {
        $header = ['Hall No', 'Center', 'QR Code Data'];
        $data = [
            ['001', '0101', 'D119201929F01000053001OF5'],
            ['001', '0101', 'D119201929F01000053002OF5'],
            ['001', '0101', 'D119201929F01000053003OF5'],
            ['001', '0101', 'D119201929F01000053004OF5'],
            ['001', '0101', 'D119201929F01000053005OF5'],
            ['001', '0101', 'D219201929A0100005300'],
            ['001', '0101', 'D219201929A0100005300'],
            ['001', '0101', 'I119201929F0100005'],
            ['001', '0101', 'I219201929F0100005'],
            ['001', '0101', 'R119201929F0100005'],
            ['001', '0101', 'I319201929F0100005'],
            ['001', '0101', 'I419201929F0100005'],
            ['001', '0101', 'I519201929F0100005'],
            ['001', '0101', 'I619201929F0100005'],
            ['001', '0101', 'I719201929F0100005'],
            ['001', '0101', 'R219201929F0100005'],
            ['001', '0101', 'R319201929F0100005'],
            ['001', '0101', 'R419201929F0100005'],
            ['001', '0101', 'R519201929F0100005'],
        ];

        $callback = function () use ($header, $data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $header);

            foreach ($data as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        $filename = "sample_exam_materials.csv";

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
        $originalContent = file_get_contents($file->getRealPath());

        // Process the content to preserve leading zeros
        $rows = array_map('str_getcsv', explode("\n", $originalContent));
        // Define the path and pattern for existing files
        $uploadedFilePath = 'uploads/csv_files/';
        $pattern = storage_path('app/public/' . $uploadedFilePath . 'ED_EXAM_MATERIALS_QR' . $examId . '_uploaded_*');
        // Find and delete existing files
        $existingFiles = glob($pattern);
        foreach ($existingFiles as $existingFile) {
            if (file_exists($existingFile)) {
                unlink($existingFile);
            }
        }
        // Create new file with preserved formatting
        $uploadedFilePath = 'uploads/csv_files/';
        $uploadedFileName = 'ED_EXAM_MATERIALS_QR' . $examId . '_uploaded_' . time() . '.csv';
        $fullPath = storage_path('app/public/' . $uploadedFilePath . $uploadedFileName);
        // Ensure directory exists
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0777, true);
        }

        // Write to new file with proper formatting
        $fp = fopen($fullPath, 'w');
        fprintf($fp, chr(0xEF) . chr(0xBB) . chr(0xBF)); // Add UTF-8 BOM

        foreach ($rows as $row) {
            //skip first row heading
            if ($row[0] == '') {
                continue;
            }
            if (!empty($row)) { // Skip empty rows
                // Format the columns that need leading zeros
                $row[0] = '="' . str_pad(trim($row[0]), 3, '0', STR_PAD_LEFT) . '"'; // Center Code
                $row[1] = '="' . str_pad(trim($row[1]), 3, '0', STR_PAD_LEFT) . '"'; // Hall Code
                fputcsv($fp, $row);
            }
        }
        fclose($fp);
        $uploadedFileUrl = url('storage/' . $uploadedFilePath . $uploadedFileName);

        $data = array_map('str_getcsv', file($file->getRealPath()));

        // Open the CSV file
        if (($handle = fopen($file->getRealPath(), 'r')) === false) {
            return redirect()->back()->with('error', 'Failed to open the CSV file.');
        }

        fgetcsv($handle); // Skip the header row
        $successfulInserts = 0;
        $failedRows = [];
        $totalRows = 0;

        // Delete old failed rows CSV files before proceeding
        $this->deleteExamMaterialsQROldFailedFiles($examId);

        // Process each row from the CSV file
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            // Skip empty rows or rows with missing required fields
            if (empty(array_filter($data))) {
                continue; // Skip this iteration if the row is empty
            }
            $totalRows++;
            try {
                // Validate the data for each field
                $this->validateExamMaterialsQRCSVRow($data, $examId, $successfulInserts, $failedRows);
            } catch (Exception $e) {
                $failedRows[] = array_merge($data, ['error' => $e->getMessage()]);
            }
        }
        fclose($handle);

        // Handle failed rows and generate a CSV file for them
        $failedCsvPath = null;
        if (!empty($failedRows)) {
            $failedCsvPath = $examId . '_ed_exam_materials_qr_failed_rows_' . time() . '.csv';
            $filePath = storage_path('app/public/uploads/failed_csv_files/' . $failedCsvPath);
            $fp = fopen($filePath, 'w');
            fputcsv($fp, [ 'Hall Code','Center Code', 'QR Code', 'Error']);
            foreach ($failedRows as $row) {
                // Format `center_code` as a string to preserve leading zeros
                $row[0] = sprintf('="%s"', $row[0]);
                $row[1] = sprintf('="%s"', $row[1]);
                fputcsv($fp, $row);
            }
            fclose($fp);
            session()->put('failed_csv_path', url('storage/uploads/failed_csv_files/' . $failedCsvPath));
            // Store the file path in session for future download
        } else {
            session()->forget('failed_csv_path'); // Clear failed file path if none exist
        }

        // Log the upload action
        $this->logExamMaterialsQRUploadAction($examId, $successfulInserts, count($failedRows), $uploadedFileUrl, $failedCsvPath);

        return redirect()->back()->with('success', "CSV processed: Total Rows: $totalRows, Successful: $successfulInserts, Failed: " . count($failedRows));
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

        // Match against all patterns
        foreach ($patterns as $category => $pattern) {
            if (preg_match($pattern, $qrCodeString, $matches)) {
                return [
                    'category' => $category,
                    'notification_no' => $matches['notification_no'],
                    'exam_date' => $matches['day'], // Assuming day is the exam date
                    'exam_session' => $matches['session'],
                    'center_code' => $matches['center_code'],
                    'hall_code' => $matches['venue_code'] ?? null,
                ];
            }
        }

        return null; // No pattern matched
    }
    /**
     * Delete old failed rows CSV files before proceeding.
     */
    private function deleteExamMaterialsQROldFailedFiles($examId)
    {
        $failedCsvFiles = glob(storage_path('app/public/uploads/failed_csv_files/' . $examId . '_ed_exam_materials_qr_failed_rows_*.csv'));

        // Loop through and delete old failed CSV files
        foreach ($failedCsvFiles as $file) {
            if (file_exists($file)) {
                unlink($file); // Delete the file
            }
        }
    }
    /**
     * Validate each row of the CSV and insert the data.
     */
    private function validateExamMaterialsQRCSVRow($data, $examId, &$successfulInserts, &$failedRows)
    {

        // Validate center code, name, date, session, and expected candidates
        if (!isset($data[0]) || !is_numeric($data[0])) {
            throw new Exception('Invalid or missing center code.');
        }
        if (!isset($data[1]) || !is_numeric($data[1])) {
            throw new Exception('Invalid or missing Hall code.');
        }
        if (!isset($data[2]) || !is_string($data[2])) {
            throw new Exception('Invalid or missing qr code data.');
        }

        if ($data) {
            $qrCodeString = $data[2] ?? null; // Assuming the third column contains QR code data
            if (!$qrCodeString) {
                $error = 'QR Code missing.';
                throw new Exception($error);
            }

            $parsedData = $this->parseQrCode($qrCodeString);
            if (!$parsedData) {
                $error = 'QR Code format invalid.';
                throw new Exception($error);
            }

            $dayToMatch = $parsedData['exam_date'];
            $examsession = $parsedData['exam_session'] == 'A' ? 'AN' : 'FN';
            $notificationNo = preg_replace('/(\d{2})(\d{4})/', '$1/$2', $parsedData['notification_no']);

            $exam = Currentexam::where('exam_main_notification', $notificationNo)
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
                throw new Exception($errorDetails);
            }

            if (!$exam) {
                $error = 'Exam not found for the given QR code data.';
                throw new Exception($error);
            }

            $firstExamSession = $exam?->examsession->first();
            if (!$firstExamSession) {
                $error = 'Exam date or session not found for the given QR code data.';
                throw new Exception($error);
            }

            $center = Center::where('center_code', $parsedData['center_code'])->first();
            if (!$center) {
                $error = 'Center not found for the given QR code data.';
                throw new Exception($error);
            }
            //TODO: ADD Check for exam date and session also and all other required conditions.
            $venueConfirmed = ExamConfirmedHalls::where('exam_id', $exam->exam_main_no)
                ->where('center_code', $parsedData['center_code'])
                ->where('hall_code', $parsedData['hall_code'])
                ->first();
            if (!$venueConfirmed) {
                $error = 'Hall not found for the given QR code data.';
                throw new Exception($error);
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
                'created_at' => now(),
                'updated_at' => now(),
            ];
            ExamMaterialsData::insert($qrCodeEntries);

        } else {
            throw new Exception('Invalid qr code model.');
        }
        $successfulInserts++;
    }
    /**
     * Log the upload action with metadata.
     */
    private function logExamMaterialsQRUploadAction($examId, $successfulInserts, $failedCount, $uploadedFileUrl, $failedCsvPath)
    {
        $currentUser = current_user();
        $userName = $currentUser ? $currentUser->display_name : 'Unknown';

        $metadata = [
            'user_name' => $userName,
            'successful_inserts' => $successfulInserts,
            'failed_count' => $failedCount,
            'uploaded_csv_link' => $uploadedFileUrl, // Include uploaded file link
            'failed_csv_link' => $failedCsvPath ? url('storage/uploads/failed_csv_files/' . $failedCsvPath) : null,
        ];

        // Check if a log already exists for this exam and task type
        $existingLog = $this->auditService->findLog([
            'exam_id' => $examId,
            'task_type' => 'ed_exam_materials_qrcode_upload',
        ]);

        if ($existingLog) {
            // Update the existing log
            $this->auditService->updateLog(
                logId: $existingLog->id,
                metadata: $metadata,
                afterState: null,
                description: 'Updated ED Exam Metarial QR Code '
            );
        } else {
            // Create a new log entry
            $this->auditService->log(
                examId: $examId,
                actionType: 'uploaded',
                taskType: 'ed_exam_materials_qrcode_upload',
                afterState: null,
                description: 'Uploaded ED Exam Metarial QR Code CSV',
                metadata: $metadata
            );
        }
    }
}
