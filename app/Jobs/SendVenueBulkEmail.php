<?php

namespace App\Jobs;

use App\Mail\VenueBulkMail;
use App\Models\Venues;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
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
    public $timeout = 0;
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
        $venues = Venues::whereNotNull('venue_email')
        ->where('venue_email', '~*', '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}$')
        ->where('venue_password', 'EMS@Venue@25')
        ->whereIn( 
            DB::raw('LOWER(venue_email)'),
            function ($query) {
                $query->select(DB::raw('LOWER(venue_email)'))
                      ->from('venue')
                      ->groupBy(DB::raw('LOWER(venue_email)'))
                      ->havingRaw('COUNT(*) = 1');
            }
        )
        ->get();
        //get 10 venues
        // $venues = Venues::whereNotNull('venue_email')->limit(3)->get();

        foreach ($venues as $venue) {
            try {
                $plainPassword = Str::random(10);
                $token = Str::random(64);
              

                Mail::to($venue->venue_email)->send(new VenueBulkMail($venue, $plainPassword, $token));

                $venue->venue_password = Hash::make($plainPassword);
                $venue->verification_token = $token;
                $venue->save();
                 
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

            usleep(1850000); // prevent throttling
        }
    }
}
 