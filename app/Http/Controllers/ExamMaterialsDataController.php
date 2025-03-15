<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessMaterailsQRCsv;
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
        $examMaterials = ExamMaterialsData::where('exam_id', $examId)->with(['district', 'center', 'venue'])->get();
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
        $uploadedFilePath = 'uploads/csv_files/';
        $uploadedFileName = 'QD_EXAM_MATERIALS_QR' . $examId . '_uploaded_' . time() . '.csv';
        $fullFilePath = storage_path("app/public/{$uploadedFilePath}{$uploadedFileName}");

        $existingFiles = glob(storage_path("app/public/{$uploadedFilePath}{$examId}_uploaded_*"));
        foreach ($existingFiles as $existingFile) {
            unlink($existingFile); // Delete old files
        }

        // Move the file to storage
        $file->move(storage_path("app/public/{$uploadedFilePath}"), $uploadedFileName);
        $uploadedFileUrl = asset('storage/' . $uploadedFilePath . $uploadedFileName);

        // Create new file with preserved formatting
        $uploadedFilePath = 'uploads/csv_files/';
        $fullPath = storage_path('app/public/' . $uploadedFilePath . $uploadedFileName);
        // Ensure directory exists
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0777, true);
        }

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
             'task_type' => 'qd_exam_materials_qrcode_upload',
         ]);
 
         if ($existingLog) {
             // Update the existing log
             $this->auditService->updateLog(
                 logId: $existingLog->id,
                 metadata: $initialMetadata,
                 description: 'Updated QD Exam Material QR Code CSV log'
             );
         } else {
             // Create a new log entry
             $this->auditService->log(
                 examId: $examId,
                 actionType: 'uploaded',
                 taskType: 'qd_exam_materials_qrcode_upload',
                 description: 'Started processing  QD Exam Material QR Code  CSV',
                 metadata: $initialMetadata
             );
         }
         // Dispatch the job to process the CSV in the background
         ProcessMaterailsQRCsv::dispatch($examId, $fullFilePath, $uploadedFileUrl, $currentUser);
 
         return redirect()->back()->with('success', 'CSV file uploaded successfully. Processing will continue in the background, and you will receive an email upon completion.');
     }


  
   

}
