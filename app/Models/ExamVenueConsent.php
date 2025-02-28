<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamVenueConsent extends Model
{
    use HasFactory;
    protected $table = 'exam_venue_consent';

    protected $casts = [
        'chief_invigilator_data' => 'array', // Cast as array
        'is_confirmed' => 'boolean', // Cast as boolean
        'order_by_id' => 'integer', // Cast as integer
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
        'chief_invigilator_data',
        'is_confirmed',
        'order_by_id',
        'updated_at',
        'created_at',
    ];

    // Relationship with the Exam model
    public function currentexam()
    {
        return $this->belongsTo(Currentexam::class, 'exam_id', 'exam_main_no');
    }

    // Relationship with the Venue model
    public function venues()
    {
        return $this->belongsTo(Venues::class, 'venue_id', 'venue_id');
    }
    // Relationship with the District model
    public function district()
    {
        return $this->belongsTo(District::class, 'district_code', 'district_code');
    }
    // Relationship with the Center model
    public function center()
    {
        return $this->belongsTo(Center::class, 'center_code', 'center_code');
    }
    // Relationship with the VenueAssignedCI model
    public function assignedCIs()
    {
        return $this->hasMany(VenueAssignedCI::class, 'venue_consent_id', 'id');
    }
    
}
