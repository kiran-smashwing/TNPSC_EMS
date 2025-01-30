<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class District extends Authenticatable
{
    use HasFactory;
    protected $casts = [
        'district_status' => 'boolean',
        'district_email_status' => 'boolean',
    ];
    protected $table = 'district';
    protected $primaryKey = 'district_id';
    public $timestamps = false;

    protected $fillable = [
        'district_code',
        'district_phone',
        'district_email',
        'district_name',
        'district_alternate_phone',
        'district_password',
        'district_website',
        'district_address',
        'district_longitude',
        'district_latitude',
        'district_image',
        'district_status',
        'district_email_status',
        'district_createdat',
        'remember_token',
        'verification_token',
    ];


    // Add timestamp for createdat
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->district_createdat = now();
        });
    }
    public function getDisplayNameAttribute()
    {
        return $this->district_name; // or whatever field you use for the name
    }

    public function getEmailDisplayAttribute()
    {
        return !empty($this->district_email) ? $this->district_email : 'No email available';
    }

    public function getProfileImageAttribute()
    {
        if (!empty($this->district_image) && file_exists(public_path('storage/' . $this->district_image))) {
            return $this->district_image;
        }

        return '/assets/images/user/collectorate.png';
    }
    public function getAuthPassword()
    {
        return $this->district_password;
    }
    public function treasuryOfficers()
    {
        return $this->hasMany(TreasuryOfficer::class, 'tre_off_district_id', 'district_code');
    }
    public function venues()
    {
        return $this->hasMany(Venues::class,'venue_district_id', 'district_code');
    }
    public function centers()
    {
        return $this->hasMany(Center::class, 'center_district_id', 'district_code');
    }
    public function mobileTeamStaffs()
    {
        return $this->hasMany(MobileTeamStaffs::class, 'mobile_district_id', 'district_code');
    }
    public function examCandidatesProjection()
    {
        return $this->hasMany(ExamCandidatesProjection::class, 'district_code', 'district_code');
    }
}
