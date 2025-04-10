<?php

namespace App\Jobs;

use App\Models\Venues;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\VenueBulkMail;
use Carbon\Carbon;

class SendVenueBulkEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $offset;
    protected $limit;

    public $timeout = 3600; // 1 hour max execution
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(int $offset = 0, int $limit = 400)
    {
        $this->offset = $offset;
        $this->limit = $limit;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $venues = Venues::whereNull('venue_password')
            ->offset($this->offset)
            ->limit($this->limit)
            ->get();

        foreach ($venues as $venue) {
            try {
                // Generate and hash password
                $plainPassword = Str::random(8);
                $venue->venue_password = Hash::make($plainPassword);

                // Generate email verification token
                $verificationToken = Str::uuid()->toString();
                $venue->verification_token = $verificationToken;
                $venue->email_verified_at = null; // Reset if needed
                $venue->save();

                // Send mail
                Mail::to($venue->venue_email)->send(
                    new VenueBulkMail($venue, $plainPassword, $verificationToken)
                );

                Log::info("Venue email sent: {$venue->venue_email}");

            } catch (\Throwable $e) {
                Log::error("Venue email failed: {$venue->venue_email}. Error: " . $e->getMessage());
            }
        }
    }
}

