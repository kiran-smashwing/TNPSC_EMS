<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamMaterialRoutes extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'exam_material_routes';

    // Primary key
    protected $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;

    // Fillable fields for mass assignment
    protected $fillable = [
        'exam_id',
        'exam_date',
        'district_code',
        'route_no',
        'driver_name',
        'driver_license',
        'driver_phone',
        'vehicle_no',
        'mobile_team_staff',
        'center_code',
        'hall_code',
        'created_at',
        'updated_at'
    ];
    // The attributes that should be hidden for arrays
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    // The attributes that should be cast to native types.
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'exam_date' => 'date',
        'hall_code' => 'array',
    ];
    // Relationships
    public function district()
    {
        return $this->belongsTo(District::class, 'district_code', 'district_code');
    }
    public function mobileTeam()
    {
        return $this->belongsTo(MobileTeamStaffs::class, 'mobile_team_staff', 'mobile_id');
    }

}
