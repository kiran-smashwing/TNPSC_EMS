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
            // Format `center_code` as a string to preserve leading zeros
            $row[0] = sprintf('="%s"', $row[0]);
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
    public function uploadCandidatesCsv(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|integer',
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $examId = $request->input('exam_id');
        $file = $request->file('csv_file');
        //TODO:Add functionality to preserve leading zeros in the CSV file and remove duplicate file already uplaoded 
        // Save the uploaded file
        $uploadedFilePath = 'uploads/csv_files/';
        $uploadedFileName = $examId . '_uploaded_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs($uploadedFilePath, $uploadedFileName, 'public');
        $uploadedFileUrl = url('storage/' . $uploadedFilePath . $uploadedFileName);

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
                // Validate the data for each field
                $this->validateRow($data, $examId, $table, $currentTime, $exam, $successfulInserts, $failedRows);
            } catch (\Exception $e) {
                $failedRows[] = array_merge($data, ['error' => $e->getMessage()]);
            }
        }

        fclose($handle);
            //TODO:Update the files to be saved in failes_csv file directory
        // Handle failed rows and generate a CSV file for them
        $failedCsvPath = null;
        if (!empty($failedRows)) {
            $failedCsvPath = $examId . '_failed_rows_' . time() . '.csv';
            $filePath = storage_path('app/public/' . $failedCsvPath);
            $fp = fopen($filePath, 'w');
            fputcsv($fp, ['Center Code', 'Center Name', 'Exam Date', 'Session', 'Expected Candidates', 'Error']);
            foreach ($failedRows as $row) {
                fputcsv($fp, $row);
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
        // Validate center code, name, date, session, and expected candidates
        if (!isset($data[0]) || !is_numeric($data[0])) {
            throw new \Exception('Invalid or missing center code.');
        }
        if (!isset($data[1]) || !\Carbon\Carbon::createFromFormat('d-m-Y', $data[1])) {
            throw new \Exception('Invalid or missing exam date.');
        }
        if (!isset($data[2]) || !in_array($data[2], ['FN', 'AN'])) {
            throw new \Exception('Invalid session. Must be FN or AN.');
        }
        if (!isset($data[3]) || !is_numeric($data[3])) {
            throw new \Exception('Invalid or missing expected candidates count.');
        }

        // Check for duplicate entry
        $duplicate = \DB::table($table)
            ->where('exam_id', $examId)
            ->where('center_code', $data[0])
            ->where('exam_date', \Carbon\Carbon::createFromFormat('d-m-Y', $data[1])->format('Y-m-d'))
            ->where('session', $data[2])
            ->first();

        if ($duplicate) {
            $errorDetails = "Duplicate entry found. Existing data - Center Code: {$duplicate->center_code},  Exam Date: {$duplicate->exam_date}, Session: {$duplicate->session}, Expected Candidates: {$duplicate->expected_candidates}, Updated At: {$duplicate->updated_at}";
            throw new \Exception($errorDetails);
        }

        // Insert data into the database
        $insertData = [
            'exam_id' => $examId,
            'created_at' => $currentTime,
            'updated_at' => $currentTime,
        ];
        $center = Center::where('center_code', $data[0])->first();
        if (!$center) {
            throw new \Exception('Center code not found.');
        }
          
            $insertData += [
                'center_code' => $center->center_code,
                'district_code' => $center->district->district_code ?? null,
                'exam_date' => \Carbon\Carbon::createFromFormat('d-m-Y', $data[1])->format('Y-m-d'),
                'session' => $data[2],
                'expected_candidates' => $data[3],
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
            'failed_csv_link' => $failedCsvPath ? url('storage/' . $failedCsvPath) : null,
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
        $failedCsvFiles = glob(storage_path('app/public/' . $examId . '_failed_rows_*.csv'));

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
            // Format `center_code` as a string to preserve leading zeros
            $row[0] = sprintf('="%s"', $row[0]);
            $row[1] = sprintf('="%s"', $row[1]);
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
        $uploadedFilePath = 'uploads/csv_files/';
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
            $failedCsvPath = $examId . '_finalizehalls_failed_rows_' . time() . '.csv';
            $filePath = storage_path('app/public/uploads/failed_csv_files/' . $failedCsvPath);
            $fp = fopen($filePath, 'w');
            fputcsv($fp, ['Center Code', 'Hall Code', 'Exam Date', 'Exam Session', 'Candidates Count', 'Error']);
            foreach ($failedRows as $row) {
                // Format `center_code` as a string to preserve leading zeros
                $row[0] = sprintf('="%s"', $row[0]);
                $row[1] = sprintf('="%s"', $row[1]);
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
        $failedCsvFiles = glob(storage_path('app/public/uploads/failed_csv_files/' . $examId . '_finalizehalls_failed_rows_*.csv'));

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

        // Validate center code, name, date, session, and expected candidates
        if (!isset($data[0]) || !is_numeric($data[0])) {
            throw new \Exception('Invalid or missing center code.');
        }
        if (!isset($data[1]) || !is_numeric($data[1])) {
            throw new \Exception('Invalid or missing Hall code.');
        }
        if (!isset($data[2]) || !\Carbon\Carbon::createFromFormat('d-m-Y', $data[2])) {
            throw new \Exception('Invalid or missing exam date.');
        }
        if (!isset($data[3]) || !in_array($data[3], ['FN', 'AN'])) {
            throw new \Exception('Invalid session. Must be FN or AN.');
        }
        if (!isset($data[4]) || !is_numeric($data[4])) {
            throw new \Exception('Invalid or missing actual candidates count.');
        }
        // Validate center code, name, date, session, in ExamConfirmedHalls 
        $confirmedByID = ExamConfirmedHalls::where('exam_id', $examId)
            ->where('hall_code', $data[1])
            ->where('exam_session', $data[3])
            ->where('exam_date', \Carbon\Carbon::createFromFormat('d-m-Y', $data[2])->format('Y-m-d'))
            ->where('center_code', $data[0])
            ->first();

        if (!$confirmedByID) {
            $errorDetails = "No Confirmed Halls found. Existing data - Center Code: {$data[0]}, Hall Code: {$data[1]},  Exam Date: {$data[2]}, Session: {$data[3]}";
            throw new \Exception($errorDetails);
        }
        // Insert data into the database
        if ($confirmedByID) {
            $confirmedByID->is_apd_uploaded = true;
            $confirmedByID->alloted_count = $data[4];
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
            'failed_csv_link' => $failedCsvPath ? url('storage/uploads/failed_csv_files/' . $failedCsvPath) : null,
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
