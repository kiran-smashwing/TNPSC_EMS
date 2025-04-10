<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Venues;
use App\Jobs\SendVenueBulkEmail;

class DispatchVenueEmails extends Command
{
    protected $signature = 'email:dispatch-venue-bulk';
    protected $description = 'Dispatch venue emails in hourly batches (300–400 per hour)';

    public function handle()
    {
        $total = Venues::whereNull('venue_password')->count();
        $batchSize = 400;

        for ($i = 0; $i < ceil($total / $batchSize); $i++) {
            SendVenueBulkEmail::dispatch($i * $batchSize, $batchSize)
                ->delay(now()->addHours($i));

            $this->info("Dispatched batch #" . ($i + 1) . " for offset " . ($i * $batchSize));
        }

        $this->info("All venue email jobs dispatched in hourly batches.");
    }
}
