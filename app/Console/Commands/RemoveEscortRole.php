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
        $today = Carbon::today();
        $thresholdDate = $today->subDays(2);
        // Fetch department officials assigned to mobile team staff
        $officials = DepartmentOfficial::where('custom_role', 'ESCORT')->get();
        foreach ($officials as $user) {
            // Collect all exam IDs from all routes assigned to this official
            $examIds = ChartedVehicleRoute::whereHas('escortstaffs', function ($query) use ($user) {
                $query->where('tnpsc_staff_id', $user->dept_off_id);
            })
                ->pluck('exam_id') // Assuming `exam_id` is stored as JSON or array
                ->toArray();
            // Flatten the array in case exam_id is stored as JSON (string)
            $examIds = array_merge([], ...array_map(fn($id) => $id ?? [], $examIds));
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

                    // Only remove the role if the latest session date is older than the threshold
                    if ($latestSessionDate && $latestSessionDate->lt($thresholdDate)) {
                        $user->custom_role = null;
                        $user->save();
                        $this->info("Removed role from Official ID: {$user->dept_off_id} - Name: {$user->dept_off_name} - Last Session: {$latestSessionDate->toDateString()}");
                    }
                } else {
                    // If no exams have sessions, optionally remove the role (adjust logic as needed)
                    $user->custom_role = null;
                    $user->save();
                    $this->info("Removed role from Official ID: {$user->dept_off_id} - Name: {$user->dept_off_name} - No sessions found");
                }
            }
        }
        $this->info('Role removal process completed.');
    }
}
