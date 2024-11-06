<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invigilator extends Model
{
    use HasFactory;

    // Set the correct table name
    protected $table = 'invigilator';  // Ensure this matches the table name in your database

    // Set the primary key for the model
    protected $primaryKey = 'invigilator_id';

    // Allow mass assignment for the specified fields
    protected $fillable = [
        'invigilator_district_id',
        'invigilator_center_id',
        'invigilator_venue_id',
        'invigilator_name',
        'invigilator_email',
        'invigilator_phone',
        'invigilator_designation',
        'invigilator_image',  // image field
    ];

    // Cast attributes to appropriate data types
    protected $casts = [
        'invigilator_createdat' => 'datetime', // Make sure the timestamp is handled as a datetime
    ];

    // Disable automatic handling of 'updated_at' column
    public function getUpdatedAtColumn()
    {
        return null;  // If you don't want an 'updated_at' column
    }

    // Define relationships with other models
    public function district()
    {
        return $this->belongsTo(District::class, 'invigilator_district_id', 'district_id');
    }

    public function center()
    {
        return $this->belongsTo(Center::class, 'invigilator_center_id', 'center_id');
    }

    public function venue()
    {
        return $this->belongsTo(Venues::class, 'invigilator_venue_id', 'venue_id');
    }
}
