<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CIMeetingQrcode extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'ci_metting_qrcode';

    // The attributes that are mass assignable
    protected $fillable = [
        'exam_id', 
        'district_code', 
        'qrcode',
        'meeting_date_time', 
        'updated_at',
        'created_at',
    ];

    // The attributes that should be cast to native types
    protected $casts = [
        'meeting_date_time' => 'datetime', // If simplified, use 'meeting_date_time'
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    // Add any necessary relationships or custom methods here
}
