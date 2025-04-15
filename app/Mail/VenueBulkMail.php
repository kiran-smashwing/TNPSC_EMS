<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VenueBulkMail extends Mailable
{
    use Queueable, SerializesModels;


    public $venue;
    public $password;
    public $token;

    public function __construct($venue, $password, $token)
    {
        $this->venue = $venue;
        $this->password = $password;
        $this->token = $token;
    }

    public function build()
    {
        $verificationLink = route('venues.verifyEmail', ['token' => urlencode($this->token)]);

        return $this->subject('TNPSC EMS போர்டலுக்கான உங்களது பயனர் கணக்கு விவரங்கள் மற்றும் பயனர் வழிகாட்டி')
            ->view('emails.venue_user_welcome')
            ->with([
                'venue_email' => $this->venue->venue_email,
                'password' => $this->password,
                'verificationLink' => $verificationLink,
            ])
            ->attach(public_path('storage/assets/assets/TNPSC_EMS_Venue_Module.pdf'), [
                'as' => 'TNPSCUserGuide.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
