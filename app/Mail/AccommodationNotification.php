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
    public function __construct($exam, $districtCode, $totalCandidates, $letterNo, $letterDate, $examController)
    {
        $this->exam = $exam;
        $this->districtCode = $districtCode;
        $this->totalCandidates = $totalCandidates;
        $this->letterNo = $letterNo;
        $this->letterDate = $letterDate;
        $this->examController = $examController;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Accommodation Requirement Notification')
                    ->view('email.accommodation_notification')
                    ->with('exam', $this->exam)
                    ->with('districtCode', $this->districtCode)
                    ->with('totalCandidates', $this->totalCandidates)
                    ->with('letterNo', $this->letterNo)
                    ->with('letterDate', $this->letterDate)
                    ->with('examController', $this->examController);
    }
    
}