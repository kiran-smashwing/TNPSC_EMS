<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Center extends Authenticatable
{
    use HasFactory;
    protected $casts = [
        'center_status' => 'boolean',
        'center_email_status' => 'boolean',
    ];
    protected $table = 'centers';
    protected $primaryKey = 'center_id';
    public $timestamps = false;

    protected $fillable = [
        'center_district_id',
        'center_name',
        'center_code',
        'center_phone',
        'center_email',
        'center_alternate_phone',
        'center_password',
        'center_address',
        'center_longitude',
        'center_latitude',
        'center_image',
        'center_status',
        'center_email_status',
        'center_createdat',
        'remember_token',
        'verification_token',
    ];
    protected $hidden = [
        'center_password',
        'remember_token',
        'verification_token',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->center_createdat = now();
        });
    }
    public function getDisplayNameAttribute()
    {
        return $this->center_name; // or whatever field you use for the name
    }
    public function getDistrictCodeAttribute()
    {
        return $this->center_district_id;
    }
    public function getEmailDisplayAttribute()
    {
        return !empty($this->center_email) ? $this->center_email : 'No email available';
    }

    public function getProfileImageAttribute()
    {
        if (!empty($this->center_image) && file_exists(public_path('storage/' . $this->center_image))) {
            return $this->center_image;
        }

        return '/assets/images/user/center.png';
    }
    public function getAuthPassword()
    {
        return $this->center_password;
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'center_district_id', 'district_code');
    }
    public function treasuryOfficers()
    {
        return $this->hasMany(TreasuryOfficer::class, 'tre_off_district_id', 'district_code');
    }
    public function cicandidatelogs()
    {
        return $this->hasMany(CICandidateLogs::class, 'center_code', 'center_code');
    }
    public function venues()
    {
        return $this->hasMany(Venues::class, 'venue_district_id', 'district_code');
    }
    public function centers()
    {
        return $this->hasMany(Center::class, 'center_district_id', 'district_code');
    }
    public function mobileTeamStaffs()
    {
        return $this->hasMany(MobileTeamStaffs::class, 'mobile_district_id', 'district_code');
    }
}
