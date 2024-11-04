<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class District extends Model
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
    ];

    protected $hidden = [
        'district_password',
    ];

    // Add timestamp for createdat
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->district_createdat = now();
        });
    }
}