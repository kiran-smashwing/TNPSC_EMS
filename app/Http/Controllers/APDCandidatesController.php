<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currentexam;
use App\Models\Center;
use App\Services\ExamAuditService;
use App\Models\ExamConfirmedHalls;

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

        // Read uploaded CSV file
        $tempFilePath = $file->getPathname();
        $rows = array_map('str_getcsv', file($tempFilePath));

        // Open new file for writing
        $fp = fopen($fullFilePath, 'w');

        foreach ($rows as $row) {
            if (!empty($row)) {
                // Add tab space only to the first column
                $row[0] = "\t" . $row[0];
            }
            fputcsv($fp, $row); // Save with correct column separation
        }

        fclose($fp);
        $uploadedFileUrl = asset('storage/' . $uploadedFilePath . $uploadedFileName);

        // Retrieve exam and its sessions
        $exam = Currentexam::where('exam_main_no', $examId)->with('examsession')->first();
        if (!$exam) {
            return redirect()->back()->with('error', 'Exam not found.');
        }

        // Open the CSV file
        if (($handle = fopen($file->getRealPath(), 'r')) === false) {
            return redirect()->back()->with('error', 'Failed to open the CSV file.');
        }
        // Remove UTF-8 BOM if present
        $bom = fgets($handle, 4);
        if (!str_starts_with($bom, "\xEF\xBB\xBF")) {
            rewind($handle);
        }
        fgetcsv($handle); // Skip the header row

        $table = 'exam_candidates_projection';
        $currentTime = now();
        $successfulInserts = 0;
        $failedRows = [];
        $totalRows = 0;

        // Delete old failed rows CSV files before proceeding
        $this->deleteOldFailedFiles($examId);

        // Process each row from the CSV file
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            $totalRows++;
            try {
                // Skip empty rows
                if (empty(array_filter($data))) {
                    continue;
                }
                // Validate the data for each field
                $this->validateRow($data, $examId, $table, $currentTime, $exam, $successfulInserts, $failedRows);
            } catch (\Exception $e) {
                $failedRows[] = array_merge($data, ['error' => $e->getMessage()]);
            }
        }

        fclose($handle);
        // Handle failed rows and generate a CSV file for them
        $failedCsvPath = null;
        if (!empty($failedRows)) {
            $failedCsvPath = 'failes_csv/' . $examId . '_failed_rows_' . time() . '.csv';
            $filePath = storage_path('app/public/' . $failedCsvPath);
            // Ensure the directory exists
            if (!file_exists(storage_path('app/public/failes_csv'))) {
                mkdir(storage_path('app/public/failes_csv'), 0777, true);
            }
            $fp = fopen($filePath, 'w');
            fputcsv($fp, ['Center Code', 'Center Name', 'Exam Date', 'Session', 'Expected Candidates', 'Error']);
            // Add tab prefix to each cell in failed rows
            foreach ($failedRows as $row) {
                $tabbedRow = array_map(function ($cell) {
                    return "\t" . $cell;
                }, $row);
                fputcsv($fp, $tabbedRow);
            }
            fclose($fp);
            session()->put('failed_csv_path', $failedCsvPath); // Store the file path in session for future download
        } else {
            session()->forget('failed_csv_path'); // Clear failed file path if none exist
        }

        // Log the upload action
        $this->logUploadAction($exam, $examId, $successfulInserts, count($failedRows), $uploadedFileUrl, $failedCsvPath);

        return redirect()->back()->with('success', "CSV processed: Total Rows: $totalRows, Successful: $successfulInserts, Failed: " . count($failedRows));
    }
    /**
     * Validate each row of the CSV and insert the data.
     */
    private function validateRow($data, $examId, $table, $currentTime, $exam, &$successfulInserts, &$failedRows)
    {
        // Clean and format the center code
        $centerCode = isset($data[0]) ? trim($data[0]) : ''; // Remove any leading/trailing spaces

        // Validate center code is numeric after trimming
        if (!is_numeric($centerCode)) {
            throw new \Exception('Invalid or missing center code.');
        }

        // Format center code to ensure 4 digits with leading zeros
        $centerCode = str_pad((string) $centerCode, 4, '0', STR_PAD_LEFT);

        // Clean other data fields
        $examDate = isset($data[1]) ? trim($data[1]) : '';
        $session = isset($data[2]) ? trim($data[2]) : '';
        $expectedCandidates = isset($data[3]) ? trim($data[3]) : '';

        // Validate other fields
        if (!$examDate || !\Carbon\Carbon::createFromFormat('d-m-Y', $examDate)) {
            throw new \Exception('Invalid or missing exam date.');
        }
        if (!$session || !in_array(strtoupper($session), ['FN', 'AN'])) {
            throw new \Exception('Invalid session. Must be FN or AN.');
        }
        if (!$expectedCandidates || !is_numeric($expectedCandidates)) {
            throw new \Exception('Invalid or missing expected candidates count.');
        }

        // Check for duplicate entry
        $duplicate = \DB::table($table)
            ->where('exam_id', $examId)
            ->where('center_code', $centerCode)
            ->where('exam_date', \Carbon\Carbon::createFromFormat('d-m-Y', $examDate)->format('Y-m-d'))
            ->where('session', strtoupper($session))
            ->first();

        if ($duplicate) {
            $errorDetails = "Duplicate entry found. Existing data - Center Code: {$duplicate->center_code},  Exam Date: {$duplicate->exam_date}, Session: {$duplicate->session}, Expected Candidates: {$duplicate->expected_candidates}, Updated At: {$duplicate->updated_at}";
            throw new \Exception($errorDetails);
        }

        // Check if center exists with formatted code
        $center = Center::where('center_code', $centerCode)->first();
        if (!$center) {
            throw new \Exception("Center code '$centerCode' not found.");
        }

        $insertData = [
            'exam_id' => $examId,
            'center_code' => $centerCode,
            'district_code' => $center->district->district_code,
            'exam_date' => \Carbon\Carbon::createFromFormat('d-m-Y', $examDate)->format('Y-m-d'),
            'session' => strtoupper($session),
            'expected_candidates' => $expectedCandidates,
            'created_at' => $currentTime,
            'updated_at' => $currentTime,
        ];

        \DB::table($table)->insert($insertData);
        $successfulInserts++;
    }

    /**
     * Log the upload action with metadata.
     */
    private function logUploadAction($exam, $examId, $successfulInserts, $failedCount, $uploadedFileUrl, $failedCsvPath)
    {
        $currentUser = current_user();
        $userName = $currentUser ? $currentUser->display_name : 'Unknown';

        $metadata = [
            'user_name' => $userName,
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

    /**
     * Delete old failed rows CSV files before proceeding.
     */
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
        // Read the original file content
        $originalContent = file_get_contents($file->getRealPath());

        // Process the content to preserve leading zeros
        $rows = array_map('str_getcsv', explode("\n", $originalContent));
        // Define the path and pattern for existing files
        $uploadedFilePath = 'uploads/csv_files/';
        $pattern = storage_path('app/public/' . $uploadedFilePath . 'APD_FINALIZE_HALLS_' . $examId . '_uploaded_*');

        // Find and delete existing files
        $existingFiles = glob($pattern);
        foreach ($existingFiles as $existingFile) {
            if (file_exists($existingFile)) {
                unlink($existingFile);
            }
        }

        // Create new file with preserved formatting
        $uploadedFileName = 'APD_FINALIZE_HALLS_' . $examId . '_uploaded_' . time() . '.csv';
        $fullPath = storage_path('app/public/' . $uploadedFilePath . $uploadedFileName);

        // Ensure directory exists
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0777, true);
        }

        // Write to new file with proper formatting
        $fp = fopen($fullPath, 'w');
        fprintf($fp, chr(0xEF) . chr(0xBB) . chr(0xBF)); // Add UTF-8 BOM

        foreach ($rows as $row) {
            // Skip first row heading
            if ($row[0] == '') {
                continue;
            }

            if (!empty($row)) { // Skip empty rows
                // Add tab space and zero-padding
                $row[0] = "\t" . str_pad(trim($row[0]), 4, '0', STR_PAD_LEFT); // Center Code (min 4 digits)
                $row[1] = "\t" . str_pad(trim($row[1]), 3, '0', STR_PAD_LEFT); // Hall Code (min 3 digits)

                // Write row to CSV file
                fputcsv($fp, $row);
            }
        }
        fclose($fp);

        $uploadedFileUrl = asset('storage/' . $uploadedFilePath . $uploadedFileName);

        // Retrieve exam and its sessions
        $exam = Currentexam::where('exam_main_no', $examId)->with('examsession')->first();
        if (!$exam) {
            return redirect()->back()->with('error', 'Exam not found.');
        }

        // Open the CSV file
        if (($handle = fopen($file->getRealPath(), 'r')) === false) {
            return redirect()->back()->with('error', 'Failed to open the CSV file.');
        }

        fgetcsv($handle); // Skip the header row
        $successfulInserts = 0;
        $failedRows = [];
        $totalRows = 0;

        // Delete old failed rows CSV files before proceeding
        $this->deleteFinalizeOldFailedFiles($examId);

        // Process each row from the CSV file
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            // Skip empty rows or rows with missing required fields
            if (empty(array_filter($data))) {
                continue; // Skip this iteration if the row is empty
            }
            $totalRows++;
            try {
                // Validate the data for each field
                $this->validateFinalizeCSVRow($data, $examId, $successfulInserts, $failedRows);
            } catch (\Exception $e) {
                $failedRows[] = array_merge($data, ['error' => $e->getMessage()]);
            }
        }

        fclose($handle);

        // Handle failed rows and generate a CSV file for them
        $failedCsvPath = null;
        if (!empty($failedRows)) {
            $failedCsvPath = 'failes_csv/' . $examId . '_finalizehalls_failed_rows_' . time() . '.csv';
            $filePath = storage_path('app/public/' . $failedCsvPath);
            // Ensure the directory exists
            if (!file_exists(storage_path('app/public/failes_csv'))) {
                mkdir(storage_path('app/public/failes_csv'), 0777, true);
            }
            $fp = fopen($filePath, 'w');
            fprintf($fp, chr(0xEF) . chr(0xBB) . chr(0xBF)); // Add UTF-8 BOM for Excel compatibility
            fputcsv($fp, ['Center Code', 'Hall Code', 'Exam Date', 'Exam Session', 'Candidates Count', 'Error']);

            foreach ($failedRows as $row) {
                // Add tab space to Center Code and Hall Code to preserve leading zeros
                $row[0] = "\t" . trim($row[0]); // Center Code
                $row[1] = "\t" . trim($row[1]); // Hall Code

                fputcsv($fp, $row);
            }
            fclose($fp);
            session()->put('failed_csv_path', $failedCsvPath); // Store the file path in session for future download
        } else {
            session()->forget('failed_csv_path'); // Clear failed file path if none exist
        }

        // Log the upload action
        $this->logFinalizeHallsUploadAction($exam, $examId, $successfulInserts, count($failedRows), $uploadedFileUrl, $failedCsvPath);

        return redirect()->back()->with('success', "CSV processed: Total Rows: $totalRows, Successful: $successfulInserts, Failed: " . count($failedRows));
    }

    /**
     * Delete old failed rows CSV files before proceeding.
     */
    private function deleteFinalizeOldFailedFiles($examId)
    {
        $failedCsvFiles = glob(storage_path('app/public/failes_csv/' . $examId . '_finalizehalls_failed_rows_*.csv'));

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
    private function validateFinalizeCSVRow($data, $examId, &$successfulInserts, &$failedRows)
    {
        // Clean and format the center code
        $centerCode = isset($data[0]) ? trim($data[0]) : '';
        $hallCode = isset($data[1]) ? trim($data[1]) : '';
        $examDate = isset($data[2]) ? trim($data[2]) : '';
        $session = isset($data[3]) ? trim($data[3]) : '';
        $candidatesCount = isset($data[4]) ? trim($data[4]) : '';

        // Validate center code
        if (!is_numeric($centerCode)) {
            throw new \Exception('Invalid or missing center code.');
        }
        // Format center code to 4 digits
        $centerCode = str_pad($centerCode, 4, '0', STR_PAD_LEFT);
        // Validate and format hall code
        if (!is_numeric($hallCode)) {
            throw new \Exception('Invalid or missing Hall code.');
        }
        // Format hall code to 3 digits
        $hallCode = str_pad($hallCode, 3, '0', STR_PAD_LEFT);
        // Validate exam date
        if (!$examDate || !\Carbon\Carbon::createFromFormat('d-m-Y', $examDate)) {
            throw new \Exception('Invalid or missing exam date.');
        }

        // Validate session
        if (!$session || !in_array(strtoupper($session), ['FN', 'AN'])) {
            throw new \Exception('Invalid session. Must be FN or AN.');
        }
        $session = strtoupper($session);

        // Validate candidates count
        if (!is_numeric($candidatesCount)) {
            throw new \Exception('Invalid or missing actual candidates count.');
        }

        // Validate in ExamConfirmedHalls 
        $confirmedByID = ExamConfirmedHalls::where('exam_id', $examId)
            ->where('hall_code', $hallCode)
            ->where('exam_session', $session)
            ->where('exam_date', \Carbon\Carbon::createFromFormat('d-m-Y', $examDate)->format('Y-m-d'))
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
    /**
     * Log the upload action with metadata.
     */
    private function logFinalizeHallsUploadAction($exam, $examId, $successfulInserts, $failedCount, $uploadedFileUrl, $failedCsvPath)
    {
        $currentUser = current_user();
        $userName = $currentUser ? $currentUser->display_name : 'Unknown';

        $metadata = [
            'user_name' => $userName,
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
