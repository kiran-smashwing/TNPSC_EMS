<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CIcandidateLogs extends Model
{
    use HasFactory;

    protected $table = 'ci_candidate_logs'; // Replace with your table name if different

    protected $fillable = [
        'exam_id',
        'center_code',
        'hall_code',
        'exam_date',
        'additional_details',
        'candidate_remarks',
        'ci_id',
        'omr_remarks',
        'candidate_attendance',
    ];

    protected $casts = [
        'additional_details' => 'array',
        'candidate_remarks' => 'array',
        'omr_remarks' => 'array',
        'candidate_attendance' => 'array',
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
}
