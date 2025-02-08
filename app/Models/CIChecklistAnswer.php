<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CIChecklistAnswer extends Model
{
    use HasFactory;

    protected $table = 'ci_checklist_answers'; // Explicitly specify the table name
    protected $primaryKey = 'id'; // Explicitly specify the primary key if necessary

    // Define the fields that are mass assignable
    protected $fillable = [
        'exam_id',
        'center_code',
        'hall_code',
        'ci_id',
        'preliminary_answer',
        'session_answer',
        'utility_answer',
        'consolidate_answer',
        'videography_answer',
    ];

    // Define the fields to be cast as specific data types (JSON as array)
    protected $casts = [
        'preliminary_answer' => 'array',
        'session_answer' => 'array',
        'utility_answer' => 'array',
        'consolidate_answer' => 'array',
        'videography_answer' => 'array', // If it's not nullable and expected as an array
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
