<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CIPaperReplacements extends Model
{
    protected $table = 'ci_paper_replacements';

    protected $fillable = [
        'exam_id',
        'center_code',
        'hall_code',
        'exam_date',
        'exam_session',
        'registration_number',
        'replacement_type',
        'old_paper_number',
        'new_paper_number',
        'replacement_reason',
        'replacement_photo',
        'ci_id',
    ];

    public $timestamps = true; // This ensures created_at and updated_at are managed
}

