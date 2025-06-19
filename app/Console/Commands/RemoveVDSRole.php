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
        \Log::info('RemoveVDSRole command started at ' . now());

        $today = Carbon::today();
        $thresholdDate = $today->subDays(2);
        // Fetch department officials assigned to mobile team staff
        $officials = DepartmentOfficial::where('custom_role', 'VDS')->get();
        \Log::info("Found {$officials->count()} officials with VDS role");

        foreach ($officials as $official) {
            \Log::info("🔍 Checking Official ID: {$official->dept_off_id}, Name: {$official->dept_off_name}");

            $latestExamDate = ExamMaterialRoutes::where('mobile_team_staff', $official->dept_off_id)
                ->latest('exam_date')
                ->value('exam_date');

            if ($latestExamDate) {
                $parsedDate = Carbon::parse($latestExamDate);
                \Log::info("  ➤ Latest Exam Date: {$parsedDate->toDateString()}, Threshold: {$thresholdDate->toDateString()}");

                if ($parsedDate->lt($thresholdDate)) {
                    $official->custom_role = null;
                    $official->save();
                    \Log::info("✅ Removed VDS role from ID: {$official->dept_off_id}, Last Exam Date: {$parsedDate->toDateString()}");
                } else {
                    \Log::info("❌ Not removed (exam date too recent): ID: {$official->dept_off_id}, Exam Date: {$parsedDate->toDateString()}");
                }
            } else {
                // No exam date found – remove role
                \Log::warning("⚠️ No exam date found for official ID: {$official->dept_off_id}, Name: {$official->dept_off_name} — Removing role.");
                $official->custom_role = null;
                $official->save();
                \Log::info("✅ Removed VDS role from ID: {$official->dept_off_id}, Reason: No exam date found");
            }
        }

        \Log::info('RemoveVDSRole command completed at ' . now());

    }
}
