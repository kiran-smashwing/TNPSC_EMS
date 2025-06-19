<?php

namespace App\Console\Commands;

use App\Models\ChartedVehicleRoute;
use App\Models\Currentexam;
use App\Models\DepartmentOfficial;
use App\Models\ExamMaterialRoutes;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RemoveEscortRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:remove-escort';
    protected $description = 'Remove Escort role from escort staffs if handover verification is completed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \Log::info('RemoveEscortRole command started at ' . now());

        $today = Carbon::today();
        $thresholdDate = $today->subDays(2);
        // Fetch department officials assigned to mobile team staff
        $officials = DepartmentOfficial::where('custom_role', 'ESCORT')->get();
        \Log::info("Found {$officials->count()} officials with ESCORT role");

        foreach ($officials as $user) {
            \Log::info("🔍 Checking Official ID: {$user->dept_off_id}, Name: {$user->dept_off_name}");

            // Collect all exam IDs from all routes assigned to this official
            $examIds = ChartedVehicleRoute::whereHas('escortstaffs', function ($query) use ($user) {
                $query->where('tnpsc_staff_id', $user->dept_off_id);
            })
                ->pluck('exam_id') // Assuming `exam_id` is stored as JSON or array
                ->toArray();
            // Flatten the array in case exam_id is stored as JSON (string)
            $examIds = array_merge([], ...array_map(fn($id) => $id ?? [], $examIds));
            \Log::info("  ➤ Found Exam IDs: " . json_encode($examIds));

            if (!empty($examIds)) {
                // Fetch exams with their latest session dates
                $exams = Currentexam::whereIn('exam_main_no', $examIds)
                    ->with([
                        'examsession' => function ($query) {
                            $query->select('exam_sess_mainid', 'exam_sess_date')
                                ->orderBy('exam_sess_date', 'desc');
                        }
                    ])
                    ->get();
                \Log::info("  ➤ Found {$exams->count()} exams with sessions");

                if ($exams->isNotEmpty()) {
                    // Determine the latest session date across all exams
                    $latestSessionDate = null;

                    foreach ($exams as $exam) {
                        $lastSessionDate = $exam->examsession->max('exam_sess_date');
                        if ($lastSessionDate) {
                            $parsedDate = Carbon::parse($lastSessionDate); // Assuming text is in a parseable format
                            if (!$latestSessionDate || $parsedDate->gt($latestSessionDate)) {
                                $latestSessionDate = $parsedDate;
                            }
                        }
                    }
                    if ($latestSessionDate) {
                        \Log::info("  ➤ Latest session date for official: {$latestSessionDate->toDateString()}");
                        if ($latestSessionDate->lt($thresholdDate)) {
                            $user->custom_role = null;
                            $user->save();
                            \Log::info("✅ Removed role: ID {$user->dept_off_id}, Last Session: {$latestSessionDate->toDateString()}");
                        } else {
                            \Log::info("❌ Not removed (session too recent): ID {$user->dept_off_id}, Session: {$latestSessionDate->toDateString()}, Threshold: {$thresholdDate->toDateString()}");
                        }
                    } else {
                        \Log::warning("⚠️ No session dates found for exams of official ID: {$user->dept_off_id}");
                    }
                } else {
                    // If no exams have sessions, optionally remove the role (adjust logic as needed)
                    $user->custom_role = null;
                    $user->save();
                    \Log::info("Removed role from Official ID: {$user->dept_off_id} - Name: {$user->dept_off_name} - No sessions found");
                }
            }
        }
        \Log::info('RemoveEscortRole command completed at ' . now());

    }
}
