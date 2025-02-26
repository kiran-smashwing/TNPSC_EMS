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
        'replacement_type_paper',
        'ci_id',
    ];

    public $timestamps = true; // This ensures created_at and updated_at are managed
    
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

