<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scribe extends Model
{
    use HasFactory;

    // Set the correct table name
    protected $table = 'scribe';  // Ensure this matches the table name in your database

    // Set the primary key for the model
    protected $primaryKey = 'scribe_id';

    // Allow mass assignment for the specified fields
    protected $fillable = [
        'scribe_district_id',
        'scribe_center_id',
        'scribe_venue_id',
        'scribe_name',
        'scribe_email',
        'scribe_phone',
        'scribe_designation',
        'scribe_image',  // image field
    ];

    // Cast attributes to appropriate data types
    protected $casts = [
        'scribe_createdat' => 'datetime', // Ensure that the timestamp is handled correctly
    ];

    // Disable automatic handling of 'updated_at' column
    public function getUpdatedAtColumn()
    {
        return null;  // If you don't want an 'updated_at' column
    }

    // Define relationships with other models
    public function district()
    {
        return $this->belongsTo(District::class, 'scribe_district_id', 'district_id');
    }

    public function center()
    {
        return $this->belongsTo(Center::class, 'scribe_center_id', 'center_id');
    }

    public function venue()
    {
        return $this->belongsTo(Venues::class, 'scribe_venue_id', 'venue_id');
    }
}
