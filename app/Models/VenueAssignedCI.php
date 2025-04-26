<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueAssignedCI extends Model
{
    use HasFactory;
    protected $table = 'venue_assigned_ci';

    protected $casts = [
        'is_confirmed' => 'boolean', // Cast as boolean
        'order_by_id' => 'integer', // Cast as integer
    ];
    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'venue_consent_id',
        'ci_id',
        'exam_date',
        'updated_at',
        'created_at',
        'is_confirmed',
        'order_by_id',
        'candidate_count',
    ];
    // Relationship with the Venue Consent Model
    public function venueConsent()
    {
        return $this->belongsTo(ExamVenueConsent::class, 'venue_consent_id', 'id');
    }
    //Relationship with the ChiefInvigilator Model
    public function chiefInvigilator()
    {
        return $this->belongsTo(ChiefInvigilator::class, 'ci_id', 'ci_id');
    }

}
