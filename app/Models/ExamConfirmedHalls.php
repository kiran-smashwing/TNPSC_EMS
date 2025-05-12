<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamConfirmedHalls extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'exam_confirmed_halls';

    // Primary key
    protected $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;

    // Fillable fields for mass assignment
    protected $fillable = [
        'district_code',
        'center_code',
        'venue_code',
        'exam_id',
        'hall_code',
        'exam_date',
        'exam_session',
        'ci_id',
        'is_apd_uploaded',
        'alloted_count',
        'addl_cand_count',
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
    ];
    // Relationships
    public function district()
    {
        return $this->belongsTo(District::class, 'district_code', 'district_code');
    }
    public function center()
    {
        return $this->belongsTo(Center::class, 'center_code', 'center_code');
    }
    public function venue()
    {
        return $this->belongsTo(Venues::class, 'venue_code', 'venue_id');
    }
    public function exam()
    {
        return $this->belongsTo(Currentexam::class, 'exam_id', 'exam_main_no');
    }
    public function chiefInvigilator()
    {
        return $this->belongsTo(ChiefInvigilator::class, 'ci_id', 'ci_id');
    }

}
