<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccommodationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $exam;
    public $districtCode;
    public $totalCandidates;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($exam, $districtCode, $totalCandidates)
    {
        $this->exam = $exam;
        $this->districtCode = $districtCode;
        $this->totalCandidates = $totalCandidates;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Accommodation Requirement Notification')
                    ->view('email.accommodation_notification');
    }
}