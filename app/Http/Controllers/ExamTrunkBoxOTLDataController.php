<?php

namespace App\Http\Controllers;

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
            ['001', '0101121', '123456,123457', '03-08-2024'],
            ['001', '0101122', '123456', '03-08-2024'],
            ['001', '0101123', '123456,34567,21345', '03-08-2024'],
            ['001', '0101124', '123456', '03-08-2024'],
            ['001', '0101125', '123456,546743', '03-08-2024'],
            ['001', '0101126', '123456,212345', '03-08-2024'],
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
        $originalContent = file_get_contents($file->getRealPath());

        // Process the content to preserve leading zeros
        $rows = array_map('str_getcsv', explode("\n", $originalContent));
        // Define the path and pattern for existing files
        $uploadedFilePath = 'uploads/csv_files/';
        $pattern = storage_path('app/public/' . $uploadedFilePath . 'ED_TRUNKBOX_QR_OTL' . $examId . '_uploaded_*');
        // Find and delete existing files
        $existingFiles = glob($pattern);
        foreach ($existingFiles as $existingFile) {
            if (file_exists($existingFile)) {
                unlink($existingFile);
            }
        }
        // Create new file with preserved formatting
        $uploadedFilePath = 'uploads/csv_files/';
        $uploadedFileName = 'ED_TRUNKBOX_QR_OTL' . $examId . '_uploaded_' . time() . '.csv';
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
            // Add a tab character or single quote before numbers to retain leading zeros in Excel
            $row = array_map(function ($value) {
                return "\t" . $value; // Adding a tab to force Excel to treat as text
            }, $row);
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
            $failedCsvPath = $examId . '_ed_trunkbox_qr_otl_failed_rows_' . time() . '.csv';
            $filePath = storage_path('app/public/uploads/failed_csv_files/' . $failedCsvPath);
            $fp = fopen($filePath, 'w');
            fputcsv($fp, ['Hall Code', 'Trunk Box', 'OTL',]);
            foreach ($failedRows as $row) {
                // Add a tab character or single quote before numbers to retain leading zeros in Excel
                $row = array_map(function ($value) {
                    return "\t" . $value; // Adding a tab to force Excel to treat as text
                }, $row);
                fputcsv($fp, $row);
            }
            fclose($fp);
            session()->put('failed_csv_path', url('storage/uploads/failed_csv_files/' . $failedCsvPath));
            // Store the file path in session for future download
        } else {
            session()->forget('failed_csv_path'); // Clear failed file path if none exist
        }

        // Log the upload action
        $this->logExamTrunkBoxQROTLUploadAction($examId, $successfulInserts, count($failedRows), $uploadedFileUrl, $failedCsvPath);

        return redirect()->back()->with('success', "CSV processed: Total Rows: $totalRows, Successful: $successfulInserts, Failed: " . count($failedRows));
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
    /**
     * Delete old failed rows CSV files before proceeding.
     */
    private function deleteExamMaterialsQROldFailedFiles($examId)
    {
        $failedCsvFiles = glob(storage_path('app/public/uploads/failed_csv_files/' . $examId . '_ed_trunkbox_qr_otl_failed_rows_*.csv'));

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
            throw new Exception('Invalid or missing Hall code.');
        }
        if (!isset($data[1]) || !is_numeric($data[1])) {
            throw new Exception('Invalid or missing Trunkbox QR code.');
        }
        if (!isset($data[2]) || !is_string($data[2])) {
            throw new Exception('Invalid or missing OTL Code.');
        }
        if (!isset($data[3]) || !is_string($data[3])) {
            throw new Exception('Invalid or missing Date.');
        }

        if ($data) {
            $qrCodeString = $data[1] ?? null; // Assuming the second column contains QR code data
            $qrCodeString = trim($qrCodeString); // Removes \t and other whitespace
            if (!$qrCodeString) {
                $error = 'QR Code missing.';
                throw new Exception($error);
            }
            // dd($qrCodeString);
            $parsedData = $this->parseQrCode($qrCodeString);
            if (!$parsedData) {
                $error = 'QR Code format invalid.';
                throw new Exception($error);
            }

            $centerCode = $parsedData['center_code'];
            // Split OTL codes by comma and encode as JSON 
           // Split OTL codes by comma and encode as JSON 
           $otlCodes = explode(',', trim($data[2]));
            $otlCodes = json_encode($otlCodes);
            $examTrunkBoxOTLDuplicateData = ExamTrunkBoxOTLData::where('exam_id', $examId)
                ->where('trunkbox_qr_code', trim($data[1]))
                ->where('center_code', $centerCode)
                ->where('hall_code', trim($data[0]))
                ->where('otl_code', $otlCodes)
                ->first();

            if ($examTrunkBoxOTLDuplicateData) {
                $errorDetails = "Duplicate Entry found. Existing data - Trunk BOX QR Code: " . $data[1] . " - Exam ID: " . $examId . " - Exam TRUNK BOX OTL Data ID: " . $examTrunkBoxOTLDuplicateData;
                throw new Exception($errorDetails);
            }
            $center = Center::where('center_code', $parsedData['center_code'])->first();
            if (!$center) {
                $error = 'Center not found for the given QR code data.';
                throw new Exception($error);
            }
            // Assuming $data[3] contains the date in some format
            $examDate = date('Y-m-d', strtotime(trim($data[3])));
            // dd($examId); 
            //TODO: ADD Check for all other required conditions. 20241126092207
            $hallConfirmed = ExamConfirmedHalls::where('exam_id', $examId)
                ->where('center_code', $parsedData['center_code'])
                ->where('hall_code', trim($data[0]))
                ->where('exam_date', $examDate)
                ->first();

            if (!$hallConfirmed) {
                $error = 'Hall not found for the given QR code data.';
                throw new Exception($error);
            }

            $qrCodeEntries[] = [
                'exam_id' => $examId,
                'district_code' => $center->center_district_id,
                'center_code' => $parsedData['center_code'],
                'hall_code' => $hallConfirmed->hall_code,
                'venue_code' => $hallConfirmed->venue_code,
                'exam_date' => $hallConfirmed->exam_date,
                'trunkbox_qr_code' => trim($data[1]),
                'otl_code' => $otlCodes,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            ExamTrunkBoxOTLData::insert($qrCodeEntries);

        } else {
            throw new Exception('Invalid qr code model.');
        }
        $successfulInserts++;
    }
    /**
     * Log the upload action with metadata.
     */
    private function logExamTrunkBoxQROTLUploadAction($examId, $successfulInserts, $failedCount, $uploadedFileUrl, $failedCsvPath)
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
            'task_type' => 'ed_exam_trunkbox_qr_otl_upload',
        ]);

        if ($existingLog) {
            // Update the existing log
            $this->auditService->updateLog(
                logId: $existingLog->id,
                metadata: $metadata,
                afterState: null,
                description: 'Updated ED Exam Trunkbox QR & OTL Code'
            );
        } else {
            // Create a new log entry
            $this->auditService->log(
                examId: $examId,
                actionType: 'uploaded',
                taskType: 'ed_exam_trunkbox_qr_otl_upload',
                afterState: null,
                description: 'Updated ED Exam Trunkbox QR & OTL Code ',
                metadata: $metadata
            );
        }
    }

}
