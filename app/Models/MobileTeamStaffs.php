<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class MobileTeamStaffs extends Authenticatable
{
    use HasFactory;
    protected $casts = [
        'mobile_status' => 'boolean',
        'mobile_email_status' => 'boolean',
    ];
    protected $table = 'mobile_team'; // Specify the table name
    protected $primaryKey = 'mobile_id'; // Primary key for the table
    public $timestamps = false;

    protected $fillable = [
        'mobile_district_id',
        'mobile_name',
        'mobile_designation',
        'mobile_phone',
        'mobile_email',
        'mobile_employeeid',
        'mobile_password',
        'mobile_image', 
        'mobile_status',
        'mobile_email_status',
        'mobile_createdat',
        'remember_token',
        'verification_token',
    ];

    // Hide sensitive fields from array or JSON output
    protected $hidden = [
        'mobile_password',
        'remember_token',
        'verification_token', // Uncomment if applicable
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->mobile_createdat = now();
        });
    }
    public function getDisplayNameAttribute()
    {
        return $this->mobile_name; // or whatever field you use for the name
    }
    public function getEmailDisplayAttribute()
    {
        return !empty($this->mobile_email) ? $this->mobile_email : 'No email available';
    }
    public function getProfileImageAttribute()
    {
        if (!empty($this->mobile_image) && file_exists(public_path('storage/' . $this->mobile_image))) {
            return $this->mobile_image;
        }

        return '/assets/images/user/avatar-4.jpg';
    }

    public function getAuthPassword()
    {
        return $this->mobile_password;
    }
    

    // Define the relationship with District
    public function district()
    {
        return $this->belongsTo(District::class, 'mobile_district_id', 'district_code');
    }
}
