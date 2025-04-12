<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CIConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $exam;
    public $CI;

    public $meetingDetails;

    /**
     * Create a new message instance.

     *
     * @param mixed $exam
     * @param mixed $CI
     * @param mixed $meetingDetails
     */
    public function __construct($exam, $CI, $meetingDetails)
    {
        $this->exam = $exam;
        $this->CI = $CI;
        $this->meetingDetails = $meetingDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('தலைமை கண்காணிப்பாளர் தேர்வு பணியிடம் உறுதிப்படுத்தல் மற்றும் கூட்ட விவரங்கள்')
                    ->view('emails.ci_exam_confirmation');
    }
}
