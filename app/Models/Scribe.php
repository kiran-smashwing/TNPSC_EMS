<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scribe extends Model
{
    use HasFactory;
    protected $casts = [
        'scribe_status' => 'boolean',
    ];

    // Set the correct table name
    protected $table = 'scribe';  // Ensure this matches the table name in your database

    // Set the primary key for the model
    protected $primaryKey = 'scribe_id';
    public $timestamps = false;

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
        'scribe_status',
        'scribe_createdat',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->scribe_createdat = now();
        });
    }
   
    // Define relationships with other models
    public function district()
    {
        return $this->belongsTo(District::class, 'scribe_district_id', 'district_code');
    }

    public function center()
    {
        return $this->belongsTo(Center::class, 'scribe_center_id', 'center_code');
    }

    public function venue()
    {
        return $this->belongsTo(Venues::class, 'scribe_venue_id', 'venue_id');
    }
  
}
