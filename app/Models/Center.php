<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Center  extends Authenticatable
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

    public function getAuthPassword()
    {
        return $this->center_password;
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'center_district_id', 'district_code');
    }
}