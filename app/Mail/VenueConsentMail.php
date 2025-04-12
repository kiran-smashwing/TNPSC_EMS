<?php

namespace App\Mail;

use App\Models\ExamSession;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VenueConsentMail extends Mailable
{
    use Queueable, SerializesModels;

 
    public $venue;
    public $exam;
    public $venueData;

    public $examDates;


    /**
     * Create a new message instance.
     */
    public function __construct($venue,$venueData, $exam)
    {
        $this->venue = $venue;
        $this->venueData = $venueData;
        $this->exam = $exam;
        $examsession = ExamSession::where('exam_sess_mainid', $exam->exam_main_no)->get();
        $this->examDates =  $examsession
        ->groupBy(function ($item) {
            return Carbon::parse($item->exam_sess_date)->format('d-m-Y');
        })
        ->keys()
        ->toArray(); // Convert to array    
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Intimation and Request for Consent – Venue Allocation for Upcoming TNPSC Examination ')
                    ->view('emails.venue_consent_simple')
                    ->with( [
                        'venueName' => $this->venueData->venue_name,
                        'venueAddress' => $this->venueData->venue_address,
                        'examName' => $this->exam->exam_main_nametamil,
                        'examDate' => $this->exam->exam_main_startdate,
                        'examDates' => $this->examDates,
                        'requiredSeats' => $this->venue['halls_count'],
                    ]);
    }
}
