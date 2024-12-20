<?php

namespace App\Http\Controllers;

use App\Models\ExamMaterialsData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
class ExamMaterialsDataController extends Controller
{
    public function index($examId)
    {
        $examMaterials = ExamMaterialsData::all();
        return view('my_exam.ExamMaterialsData.index', compact('examMaterials','examId'));
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
        dd($request->all());
        // Validate the incoming request
        $request->validate([
            'exam_id' => 'required|exists:currentexams,exam_main_no',
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');

        // Open the file for reading
        if (($handle = fopen($file->getRealPath(), 'r')) === false) {
            return redirect()->back()->with('error', 'Failed to open the CSV file.');
        }

        // Skip the header row
        $header = fgetcsv($handle);

        // Define the expected columns
        $expectedColumns = [
            'id', 'exam_id', 'district_code', 'center_code', 'hall_code', 
            'exam_date', 'exam_session', 'qr_code', 'category', 
            'center_id', 'mobile_team_id', 'ci_id'
        ];

        // Check if the CSV file has the expected structure
        if ($header !== $expectedColumns) {
            fclose($handle);
            return redirect()->back()->with('error', 'Invalid CSV format.');
        }

        $rows = [];
        $failedRows = [];
        $successfulInserts = 0;

        // Process each row
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            $row = array_combine($header, $data);

            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            try {
                // Insert data into the table
                ExamMaterialsData::create([
                    'exam_id'       => $row['exam_id'],
                    'district_code' => $row['district_code'],
                    'center_code'   => $row['center_code'],
                    'hall_code'     => $row['hall_code'],
                    'exam_date'     => \Carbon\Carbon::createFromFormat('Y-m-d', $row['exam_date']),
                    'exam_session'  => $row['exam_session'],
                    'qr_code'       => $row['qr_code'],
                    'category'      => $row['category'],
                    'center_id'     => $row['center_id'],
                    'mobile_team_id'=> $row['mobile_team_id'],
                    'ci_id'         => $row['ci_id'],
                ]);

                $successfulInserts++;
            } catch (\Exception $e) {
                // Capture failed rows and their errors
                $row['error'] = $e->getMessage();
                $failedRows[] = $row;
            }
        }

        fclose($handle);

        // Handle failed rows (optional: save them for review)
        if (!empty($failedRows)) {
            $failedCsvPath = 'uploads/failed_rows_' . time() . '.csv';
            $filePath = storage_path('app/public/' . $failedCsvPath);
            $fp = fopen($filePath, 'w');
            fputcsv($fp, array_merge($expectedColumns, ['error']));
            foreach ($failedRows as $failedRow) {
                fputcsv($fp, $failedRow);
            }
            fclose($fp);

            session()->put('failed_csv_path', $failedCsvPath); // Store the file path in session
        }

        // Return success or failure message
        return redirect()->back()->with('success', "CSV processed: Successful rows: $successfulInserts, Failed rows: " . count($failedRows));
    }
}
