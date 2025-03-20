<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserEmailVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $verification_link;

    /**
     * Create a new message instance.
     *
     * @param string $name
     * @param string $email
     * @param string $verification_link
     */
    public function __construct($name, $email, $verification_link)
    {
        $this->name = $name;
        $this->email = $email;
        $this->verification_link = $verification_link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Verify Your Email Address - TNPSC EMS')
                    ->view('email.user_email_verification');
    }
}
