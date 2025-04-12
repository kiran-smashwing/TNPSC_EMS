<?php

namespace App\Jobs;

use App\Mail\VenueBulkMail;
use App\Models\Venues;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Mail\VenueAccountMail;
use App\Models\EmailVerification;
use Carbon\Carbon;

class SendVenueBulkEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        // No parameters needed
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // $venues = Venues::whereNull('venue_password')
        //     ->whereNotNull('venue_email')
        //     ->get();
        //get 10 venues
        $venues = Venues::whereNotNull('venue_email')->limit(3)->get();

        foreach ($venues as $venue) {
            try {
                $plainPassword = Str::random(10);
                $token = Str::random(64);

                $venue->venue_password = Hash::make($plainPassword);
                $venue->verification_token = $token;
                $venue->save();

                Mail::to($venue->venue_email)->send(new VenueBulkMail($venue, $plainPassword, $token));

                // Enhanced success log
                Log::channel('venue_email')->info('Mail Sent', [
                    'email' => $venue->venue_email,
                    'venue_id' => $venue->venue_id,
                    'time' => Carbon::now()->toDateTimeString(),
                ]);
            } catch (\Exception $e) {
                // Enhanced error log
                Log::channel('venue_email')->error('Mail Failed', [
                    'email' => $venue->venue_email,
                    'venue_id' => $venue->venue_id,
                    'error' => $e->getMessage(),
                    'time' => Carbon::now()->toDateTimeString(),
                ]);
            }

            sleep(9); // prevent throttling
        }
    }
}
