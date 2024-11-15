<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TreasuryOfficer extends Authenticatable
{
    use HasFactory;

    protected $casts = [
        'tre_off_status' => 'boolean',
        'tre_off_email_status' => 'boolean',
    ];
    protected $table = 'treasury_officer';
    protected $primaryKey = 'tre_off_id';
    public $timestamps = false;

    protected $fillable = [
        'tre_off_district_id',
        'tre_off_name',
        'tre_off_designation',
        'tre_off_phone',
        'tre_off_email',
        'tre_off_employeeid',
        'tre_off_password',
        'tre_off_createdat',
        'tre_off_image',
        'tre_off_status',
        'tre_off_email_status',
        'remember_token',
    ];
    protected $hidden = [
        'tre_off_password',
        'remember_token'
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->tre_off_createdat = now();
        });
    }

    public function getAuthPassword()
    {
        return $this->tre_off_password;
    }
    public function getDisplayNameAttribute()
    {
        return $this->tre_off_name; // or whatever field you use for the name
    }
    public function district()
    {
        return $this->belongsTo(District::class, 'tre_off_district_id', 'district_id');
    }
    
}
