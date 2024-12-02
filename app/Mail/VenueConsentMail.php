<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VenueConsentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $venue;
    public $exam;
    public $loginUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($venue, $exam)
    {
        $this->venue = $venue;
        $this->exam = $exam;
        $this->loginUrl = route('login'); // Assuming you have a named route for venue login
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Exam Venue Consent Request - ' . $this->exam->exam_main_name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'email.venue_consent_simple',
            with: [
                'venueName' => $this->venue->venue_name,
                'venueAddress' => $this->venue->venue_address,
                'examName' => $this->exam->exam_main_name,
                'examDate' => $this->exam->exam_main_startdate,
                'requiredHalls' => ceil($this->venue->total_accommodation / 200),
                'loginUrl' => $this->loginUrl
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
        return [];
    }
}