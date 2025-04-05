<?php

namespace App\Mail;

use App\Models\District;
use App\Models\ExamSession;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccommodationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $exam;
    public $district;
    public $totalCandidates;
    public $examDates;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($exam, $district, $totalCandidates)
    {
        $this->exam = $exam;
        $this->district = $district;
        $this->totalCandidates = $totalCandidates;
        $examsession = ExamSession::where('exam_sess_mainid', $exam->exam_main_no)->get();
        $this->examDates =  $examsession
        ->groupBy(function ($item) {
            return Carbon::parse($item->exam_sess_date)->format('d-m-Y');
        })
        ->keys()
        ->toArray(); // Convert to array    
        // $this->letterNo = $letterNo;
        // $this->letterDate = $letterDate;
        // $this->examController = $examController;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Request for Venue Securing for Upcoming TNPSC Examination')
                    ->view('email.accommodation_notification')
                    ->with( [
                        'exam' => $this->exam,
                        'district' => $this->district,
                        'examDates' => $this->examDates,
                        'totalCandidates' => $this->totalCandidates
                    ]);
    }
    
}