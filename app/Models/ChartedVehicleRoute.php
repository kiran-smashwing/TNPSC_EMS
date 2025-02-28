<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartedVehicleRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_no',
        'exam_id',
        'charted_vehicle_no',
        'driver_details',
        'otl_locks',
        'gps_locks',
        'pc_details',
        'escort_vehicle_details',
        'handover_verification_details',
        'handover_verification_status',
        'used_otl_locks',
        'used_gps_lock',
        'charted_vehicle_verification',
    ];

    protected $casts = [
        'exam_id' => 'json',
        'driver_details' => 'json',
        'otl_locks' => 'json',
        'gps_locks' => 'json',
        'pc_details' => 'json',
        'escort_vehicle_details' => 'json',
        'charted_vehicle_verification' => 'json',

    ];

    public function escortstaffs()
    {
        return $this->hasMany(EscortStaff::class, 'charted_vehicle_id');
    }

    

}
