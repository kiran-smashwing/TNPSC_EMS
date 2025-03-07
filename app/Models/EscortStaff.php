<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EscortStaff extends Model
{
    use HasFactory;

    protected $table = 'escort_staffs';

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
    
    public function chartedVehicleRoute()
    {
        return $this->belongsTo(ChartedVehicleRoute::class, 'charted_vehicle_id', 'id');
    }
    public function district()
    {
        return $this->belongsTo(District::class, 'district_code', 'district_code');
    }
    public function departmentOfficials(){
        return $this->belongsTo(DepartmentOfficial::class, 'tnpsc_staff_id', 'dept_off_id');
    }
}

