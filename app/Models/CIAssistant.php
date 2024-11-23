<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CIAssistant extends Model
{
    use HasFactory;
    protected $casts = [
        'cia_status' => 'boolean',
    ];

    // Set the correct table name
    protected $table = 'cheif_invigilator_assistant';

    // Set the primary key for the model
    protected $primaryKey = 'cia_id';

    public $timestamps = false;

    // Allow mass assignment for the specified fields
    protected $fillable = [
        'cia_district_id',
        'cia_center_id',
        'cia_venue_id',
        'cia_name',
        'cia_email',
        'cia_phone',
        'cia_designation',
        'cia_image', 
        'cia_createdat',
        'cia_status',
    ];

      // Add timestamp for createdat
      protected static function boot()
      {
          parent::boot();
          static::creating(function ($model) {
              $model->cia_createdat = now();
          });
      }

    // Define relationships with other models
    public function district()
    {
        return $this->belongsTo(District::class, 'cia_district_id', 'district_code');
    }

    public function center()
    {
        return $this->belongsTo(Center::class, 'cia_center_id', 'center_code');
    }

    public function venue()
    {
        return $this->belongsTo(Venues::class, 'cia_venue_id', 'venue_code');
    }
}
