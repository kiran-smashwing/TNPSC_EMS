<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invigilator extends Model
{
    use HasFactory;
    protected $casts = [
        'invigilator_status' => 'boolean',
    ];
    // Set the correct table name
    protected $table = 'invigilator';  // Ensure this matches the table name in your database

    // Set the primary key for the model
    protected $primaryKey = 'invigilator_id';
    public $timestamps = false;  // Disable Laravel's default timestamp fields

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
        'invigilator_status',
        'invigilator_createdat',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->invigilator_createdat = now();
        });
    }

    // Define relationships with other models
    public function district()
    {
        return $this->belongsTo(District::class, 'invigilator_district_id', 'district_code');
    }

    public function center()
    {
        return $this->belongsTo(Center::class, 'invigilator_center_id', 'center_code');
    }

    public function venue()
    {
        return $this->belongsTo(Venues::class, 'invigilator_venue_id', 'venue_code');
    }

    public function chiefinvigilator()
    {
        return $this->belongsTo(ChiefInvigilator::class, 'venue_code', 'ci_venue_id');
    }
    public function invigilator()
    {
        return $this->belongsTo(Invigilator::class, 'venue_code', 'invigilator_venue_id');
    }
    public function cia()
    {
        return $this->belongsTo(CIAssistant::class, 'venue_code', 'cia_venue_id');
    }
}
