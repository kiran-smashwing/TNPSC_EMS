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
    ];

    protected $casts = [
        'additional_details' => 'array',
        'candidate_remarks' => 'array',
        'omr_remarks' => 'array',
    ];
}
