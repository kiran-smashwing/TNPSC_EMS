<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserAccountCreationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $password;
    public $verification_link;

    /**
     * Create a new message instance.
     *
     * @param string $name
     * @param string $email
     * @param string $password
     * @param string $verification_link
     */
    public function __construct($name, $email, $password,$verification_link)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->verification_link = $verification_link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welcome to TNPSC EMS, ' . $this->name . '! Your Account Details Inside.')
                    ->view('emails.user_account_created')
                    ->attach(public_path('storage/assets/assets/TNPSC EMS-District Module.pdf'), [
                        'as' => 'TNPSCUserGuide.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}
