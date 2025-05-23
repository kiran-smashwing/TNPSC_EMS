<?php 

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CsvProcessingResult extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $subject;

    public function __construct($data, $subject)
    {
        $this->data = $data;
        $this->subject = $subject;
    }

    public function build()
    {
        return $this->subject($this->subject)
            ->view('emails.csv_processing_result')
            ->with('data', $this->data);
    }
}