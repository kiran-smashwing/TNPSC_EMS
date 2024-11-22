<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSession extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'exam_session';

    // Specify the primary key
    protected $primaryKey = 'exam_session_id';

    // Disable timestamps as the table does not have created_at and updated_at columns
    public $timestamps = false;

    // Specify the fillable attributes
    protected $fillable = [
        'exam_session_id',
        'exam_sess_mainid',
        'exam_sess_date',
        'exam_sess_session',
        'exam_sess_time',
        'exam_sess_duration',
        'exam_sess_subject',
        'exam_sess_flag',
        'exam_sess_createdat',
    ];

    // Specify the casts for attributes
    protected $casts = [
        'exam_sess_createdat' => 'datetime',
    ];

    // Define the boot method to handle model events
    protected static function boot()
    {
        parent::boot();

        // Automatically set the created_at timestamp when creating a new record
        static::creating(function ($model) {
            $model->exam_sess_createdat = now();
        });
    }
    public function examsessions()
    {
        return $this->hasMany(Examsession::class, 'exam_sess_mainid', 'exam_main_no');
    }

}