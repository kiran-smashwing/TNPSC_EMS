<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EscortStaff extends Model
{
    use HasFactory;

    protected $fillable = [
        'charted_vehicle_id',
        'district_code',
        'tnpsc_staff_id',
        'si_details',
        'revenue_staff_details'
    ];

    protected $casts = [
        'si_details' => 'json',
        'revenue_staff_details' => 'json'
    ];
}
