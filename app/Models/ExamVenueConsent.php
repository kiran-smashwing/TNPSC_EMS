<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamVenueConsent extends Model
{
    use HasFactory;
    protected $table = 'exam_venue_consent';

    protected $casts = [
        'chief_invigilator_ids' => 'array', // Cast as array
    ];
    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'exam_id',
        'venue_id',
        'center_code',
        'district_code',
        'consent_status',
        'email_sent_status',
        'expected_candidates_count',
        'chief_invigilator_ids',
        'updated_at',
        'created_at',
    ];

    // Relationship with the Exam model
    public function Currentexam()
    {
        return $this->belongsTo(Currentexam::class, 'exam_id', 'exam_main_no');
    }

    // Relationship with the Venue model
    public function Venues()
    {
        return $this->belongsTo(Venues::class, 'venue_id', 'venue_code');
    }
}
