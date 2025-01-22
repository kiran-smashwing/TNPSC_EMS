<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Venues extends Authenticatable
{
    use HasFactory;

    protected $table = 'venue'; // Specify the table name
    protected $primaryKey = 'venue_id'; // Primary key for the table
    public $timestamps = false; // Disable Laravel's timestamps (created_at, updated_at)
    protected $casts = [
        'venue_status' => 'boolean',
        'venue_email_status' => 'boolean',
    ];

    protected $fillable = [
        'venue_district_id',
        'venue_center_id',
        'venue_name',
        'venue_code',
        'venue_codeprovider',
        'venue_email',
        'venue_phone',
        'venue_alternative_phone',
        'venue_type',
        'venue_category',
        'venue_website',
        'venue_password',
        'venue_address',
        'venue_distance_railway',
        'venue_treasury_office',
        'venue_longitude',
        'venue_latitude',
        'venue_bank_name',
        'venue_account_name',
        'venue_account_number',
        'venue_branch_name',
        'venue_account_type',
        'venue_ifsc',
        'venue_createdat',
        'venue_image',
        'venue_status',
        'venue_email_status',
        'remember_token',
    ];

    // Add timestamp for createdat
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->venue_createdat = now();
        });
    }

    public function getDisplayNameAttribute()
    {
        return $this->venue_name; // or whatever field you use for the name
    }
    public function getEmailDisplayAttribute()
    {
        return !empty($this->venue_email) ? $this->venue_email : 'No email available';
    }

    public function getProfileImageAttribute()
    {
        if (!empty($this->venue_image) && file_exists(public_path('storage/' . $this->venue_image))) {
            return $this->venue_image;
        }

        return '/assets/images/user/venue.png';
    }
    public function getAuthPassword()
    {
        return $this->venue_password;
    }

    // Define the relationship with District (if applicable)
    public function district()
    {
        return $this->belongsTo(District::class, 'venue_district_id', 'district_code');
    }

    // Define the relationship with Center (if applicable)
    public function center()
    {
        return $this->belongsTo(Center::class, 'venue_center_id', 'center_code');
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
