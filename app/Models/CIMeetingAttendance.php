<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CIMeetingAttendance extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'ci_meeting_attendance';

    // The attributes that are mass assignable
    protected $fillable = [
        'exam_id',
        'district_code',
        'center_code',
        'hall_code',
        'ci_id',
        'adequacy_check',
        'updated_at',
        'created_at',
    ];

    // The attributes that should be cast to native types
    protected $casts = [
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
        'adequacy_check' => 'array',
    ];
    public function center()
    {
        return $this->belongsTo(Center::class, 'center_code', 'center_code');
    }


    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'center_district_id');
    }
    public function ci()
    {
        return $this->belongsTo(ChiefInvigilator::class, 'ci_id', 'ci_id');
    }
    // Add any necessary relationships or custom methods here
}
