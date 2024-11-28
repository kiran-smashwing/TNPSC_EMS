<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;
use App\Models\Currentexam;
use App\Models\Center;
use App\Services\ExamAuditService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccommodationNotification;
class IDCandidatesController extends Controller
{
    protected $auditService;

    public function __construct(ExamAuditService $auditService)
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
        $this->auditService = $auditService;
    }

    public function updatePercentage(Request $request)
    {
        // Validate the request
        $request->validate([
            'exam_id' => 'required|integer',
            'increment_percentage' => 'required|integer|min:1|max:100',
        ]);

        $examId = $request->input('exam_id');
        $percentage = $request->input('increment_percentage');

        // Retrieve the exam and its related candidates
        $exam = Currentexam::where('exam_main_no', $examId)->first();
        if (!$exam) {
            return redirect()->back()->with('error', 'Exam not found.');
        }

        // Update the candidate count for all rows related to the exam
        DB::table('exam_candidates_projection')
            ->where('exam_id', $examId)
            ->update([
                'accommodation_required' => DB::raw("expected_candidates + (expected_candidates * $percentage / 100)"),
                'increment_percentage' => $percentage,
            ]);

        // Log the update action
        $this->logUpdateAction($exam, $examId, $percentage);

        return redirect()->back()->with('success', 'Candidate counts updated successfully.');
    }

    /**
     * Log the update action with metadata.
     */
    private function logUpdateAction($exam, $examId, $percentage)
    {
        $currentUser = current_user();
        $userName = $currentUser ? $currentUser->display_name : 'Unknown';

        $metadata = [
            'user_name' => $userName,
            'increment_percentage' => $percentage,
        ];

        // Check if a log already exists for this exam and task type
        $existingLog = $this->auditService->findLog([
            'exam_id' => $examId,
            'task_type' => 'id_candidates_update_percentage',
        ]);

        if ($existingLog) {
            // Update the existing log
            $this->auditService->updateLog(
                logId: $existingLog->id,
                metadata: $metadata,
                afterState: $exam->toArray(),
                description: 'Updated candidate counts by percentage'
            );
        } else {
            // Create a new log entry
            $this->auditService->log(
                examId: $examId,
                actionType: 'updated',
                taskType: 'id_candidates_update_percentage',
                afterState: $exam->toArray(),
                description: 'Updated candidate counts by percentage',
                metadata: $metadata
            );
        }
    }

    public function downloadUpdatedCountCsv($examId)
    {
        // Retrieve the updated candidate counts for the given exam ID
        $candidates = DB::table('exam_candidates_projection')
            ->where('exam_id', $examId)
            ->get(['center_code', 'center_name', 'exam_date', 'session', 'expected_candidates', 'accommodation_required', 'increment_percentage']);

        if ($candidates->isEmpty()) {
            return redirect()->back()->with('error', 'No data found for the given exam ID.');
        }

        // Create a CSV file
        $filename = "updated_{$candidates[0]->increment_percentage}_counts_exam_{$examId}.csv";
        $handle = fopen($filename, 'w');
        fputcsv($handle, ['Center Code', 'Center Name', 'Date', 'Session', 'Count', 'Accommodation Required']);

        foreach ($candidates as $candidate) {
            fputcsv($handle, [
                $candidate->center_code,
                $candidate->center_name,
                $candidate->exam_date,
                $candidate->session,
                $candidate->expected_candidates,
                $candidate->accommodation_required,
            ]);
        }

        fclose($handle);

        // Return the CSV file as a download response
        return response()->download($filename)->deleteFileAfterSend(true);
    }
    public function showDistrictIntimationForm($examId)
    {
        // Retrieve and group candidates by district code, calculating totals in the query itself
        $districts = DB::table('exam_candidates_projection')
            ->select(
                'district_code',
                DB::raw('SUM(accommodation_required) as total_accommodation_required'),
                DB::raw('COUNT(DISTINCT center_code) as center_count')
            )
            ->where('exam_id', $examId)
            ->groupBy('district_code')
            ->get();

        if ($districts->isEmpty()) {
            return redirect()->back()->with('error', 'No data found for the given exam ID.');
        }

        // Retrieve total districts and count of districts present in the grouped data
        $totalDistricts = DB::table('district')->count();
        $groupedDistrictCount = $districts->count();

        // Map district names from the district table
        $districtNames = DB::table('district')
            ->whereIn('district_code', $districts->pluck('district_code'))
            ->pluck('district_name', 'district_code');

        // Format the result to include district names
        $districts = $districts->map(function ($district) use ($districtNames) {
            return [
                'district_code' => $district->district_code,
                'district_name' => $districtNames[$district->district_code] ?? 'Unknown',
                'total_accommodation_required' => $district->total_accommodation_required,
                'center_count' => $district->center_count,
            ];
        });
        // Pass data to the view
        return view('my_exam.IDCandidates.district-intimation', compact('examId', 'districts', 'totalDistricts', 'groupedDistrictCount'));
    }

    public function sendAccommodationEmail(Request $request)
    {

        $request->validate([
            'exam_id' => 'required|integer',
            'district_codes' => 'required|array|min:1',
        ]);

        $examId = $request->input('exam_id');
        $districtCodes = $request->input('district_codes');

        // Retrieve the exam and its related candidates
        $exam = Currentexam::where('exam_main_no', $examId)->first();
        if (!$exam) {
            return redirect()->back()->with('error', 'Exam not found.');
        }

        foreach ($districtCodes as $districtCode) {
            // Retrieve the centers in the specified district
            $district = District::where('district_code', $districtCode)->get();
            if ($district->isEmpty()) {
                continue; // Skip if no centers found for the specified district
            }
            // Calculate the required accommodations
            $totalCandidates = DB::table('exam_candidates_projection')
                ->where('exam_id', $examId)
                ->where('district_code', $districtCode)
                ->sum('accommodation_required');

            // Send the email notification
            Mail::to('kiran@smashwing.com')->send(new AccommodationNotification($exam, $districtCode, $totalCandidates));
        }

    }
}