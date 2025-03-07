<?php

namespace App\Console\Commands;

use App\Models\ExamMaterialRoutes;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\DepartmentOfficial;

class RemoveVDSRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:remove-vds';
    protected $description = 'Remove VDS role from Department Officials if the latest assigned exam date is more than 2 days old';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        $thresholdDate = $today->subDays(2);
        // Fetch department officials assigned to mobile team staff
        $officials = DepartmentOfficial::where('custom_role', 'VDS')->get();

        foreach ($officials as $official) {
            $latestExamDate = ExamMaterialRoutes::where('mobile_team_staff', $official->dept_off_id)
                ->latest('exam_date')
                ->value('exam_date');

            if ($latestExamDate && Carbon::parse($latestExamDate)->lt($thresholdDate)) {
                $official->custom_role = null; // Remove VDS role
                $official->save();
                $this->info("Removed VDS role from Department Official ID: {$official->dept_off_id} - Name: {$official->dept_off_name}");
            }
        }

        $this->info('VDS role removal process completed.');
    }
}
