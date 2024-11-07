<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheifInvigilatorAssistant extends Model
{
    use HasFactory;

    // Set the correct table name
    protected $table = 'cheif_invigilator_assistant';

    // Set the primary key for the model
    protected $primaryKey = 'cia_id';

    // Allow mass assignment for the specified fields
    protected $fillable = [
        'cia_district_id',
        'cia_center_id',
        'cia_venue_id',
        'cia_name',
        'cia_email',
        'cia_phone',
        'cia_designation',
        'cia_image', // Image field if storing image path
    ];

    // Cast attributes to appropriate data types
    protected $casts = [
        'cia_createdat' => 'datetime', // Ensures timestamp handling for `cia_createdat`
    ];

    // Disable automatic handling of 'updated_at' column
    public function getUpdatedAtColumn()
    {
        return null; // Disables automatic 'updated_at' handling
    }

    // Define relationships with other models
    public function district()
    {
        return $this->belongsTo(District::class, 'cia_district_id', 'district_id');
    }

    public function center()
    {
        return $this->belongsTo(Center::class, 'cia_center_id', 'center_id');
    }

    public function venue()
    {
        return $this->belongsTo(Venues::class, 'cia_venue_id', 'venue_id');
    }
}
