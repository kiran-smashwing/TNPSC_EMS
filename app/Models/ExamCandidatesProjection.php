<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamCandidatesProjection extends Model
{
    use HasFactory;

    // Table associated with the model
    protected $table = 'exam_candidates_projection';

    // The attributes that are mass assignable
    protected $fillable = [
        'exam_id', 
        'exam_date', 
        'session', 
        'center_code', 
        'district_code',
        'expected_candidates',
        'actual_candidates',
        'accommodation_required',
        'accommodation_received'
    ];

    // The attributes that should be cast to native types (e.g. DateTime)
    protected $casts = [
        'exam_date' => 'date',  // Cast exam_date to Date format
        'expected_candidates' => 'integer',
        'actual_candidates' => 'integer',
        'accommodation_required' => 'integer',
        'accommodation_received' => 'integer',
    ];

    public function district()
    {
        return $this->belongsTo(District::class, 'district_code', 'district_code');
    }

}
