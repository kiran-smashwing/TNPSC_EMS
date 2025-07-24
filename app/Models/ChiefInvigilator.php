<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ChiefInvigilator extends Authenticatable
{
    use HasFactory;
    protected $casts = [
        'ci_status' => 'boolean',
        'ci_email_status' => 'boolean',
    ];
    protected $table = 'cheif_invigilator';
    protected $primaryKey = 'ci_id';
    public $timestamps = false;

    protected $fillable = [
        'ci_district_id',
        'ci_center_id',
        'ci_venue_id',
        'ci_name',
        'ci_email',
        'ci_phone',
        'ci_alternative_phone',
        'ci_designation',
        'ci_password',
        'ci_image',
        'ci_status',
        'ci_email_status',
        'ci_createdat',
        'remember_token',
        'ci_employee_id',
    ];
    protected $hidden = [
        'ci_password',
        'remember_token',
    ];
    // Add timestamp for createdat
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->ci_createdat = now();
        });
    }
    public function getDisplayNameAttribute()
    {
        return $this->ci_name; // or whatever field you use for the name
    }
    public function getDistrictCodeAttribute()
    {
        return $this->ci_district_id; // or whatever field you use for the name
    }
    public function getCenterCodeAttribute()
    {
        return $this->ci_center_id; // or whatever field you use for the name
    }
    public function getVenueCodeAttribute()
    {
        return $this->ci_venue_code; // or whatever field you use for the name
    }
    public function getVenueIdAttribute()
    {
        return $this->ci_venue_id; // or whatever field you use for the name
    }
    public function getEmailDisplayAttribute()
    {
        return !empty($this->ci_email) ? $this->ci_email : 'No email available';
    }
    public function getProfileImageAttribute()
    {
        if (!empty($this->ci_image) && file_exists(public_path('storage/' . $this->ci_image))) {
            return $this->ci_image;
        }

        return '/assets/images/user/avatar-4.jpg';
    }
    public function getAuthPassword()
    {
        return $this->ci_password;
    }
    public function district()
    {
        return $this->belongsTo(District::class, 'ci_district_id', 'district_code');
    }
    public function center()
    {
        return $this->belongsTo(Center::class, 'ci_center_id', 'center_code');
    }
    public function venue()
    {
        return $this->belongsTo(Venues::class, 'ci_venue_id', 'venue_id');
    }

}
