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
                // Get all exam dates for the collected exam IDs
                $examDates = Currentexam::whereIn('exam_main_no', $examIds)
                    ->pluck('exam_main_lastdate')
                    ->toArray();
                if (!empty($examDates)) {
                    // Get the most recent exam date
                    $latestExamDate = max($examDates);

                    // Only remove the role if the latest exam is older than the threshold
                    if (Carbon::parse($latestExamDate)->lt($thresholdDate)) {
                        $user->custom_role = null;
                        $user->save();
                        $this->info("Removed role from Official ID: {$user->dept_off_id} - Name: {$user->dept_off_name} - Last Exam: {$latestExamDate}");
                    }
                }
            }
        }
        $this->info('Role removal process completed.');
    }
}
