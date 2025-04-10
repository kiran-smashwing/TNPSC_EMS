<?php

namespace App\Mail;

use App\Models\ExamSession;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VenueBulkMail extends Mailable
{
    use Queueable, SerializesModels;


    public $venue, $plainPassword, $verificationToken;

    public function __construct($venue, $plainPassword, $verificationToken)
    {
        $this->venue = $venue;
        $this->plainPassword = $plainPassword;
        $this->verificationToken = $verificationToken;
    }

    public function build()
    {
        $link = url('/venue/verify-email/' . $this->verificationToken);

        return $this->subject('Your Venue Login Details')
            ->view('emails.venue_account')
            ->with([
                'venueName' => $this->venue->venue_name,
                'email' => $this->venue->venue_email,
                'password' => $this->plainPassword,
                'verificationLink' => $link,
            ]);
    }

}
