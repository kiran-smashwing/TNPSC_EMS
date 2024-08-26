<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collectorate extends Model
{
    use HasFactory;

    protected $table = 'collectorate';
    protected $primaryKey = 'id';

    protected $fillable = [
        'image',
        'district_name',
        'district_code',
        'address',
        'mail',
        'website',
        'mail_verify_status',
        'phone',
        'alternate_phone',
        'latitude',
        'longitude',
        'password',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'mail_verify_status' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'status' => 'string',
    ];

    public function centers()
    {
        return $this->hasMany(Center::class, 'district_id');
    }
    
}