<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currentexam extends Model
{
    use HasFactory;

    protected $table = 'exam_main';
    protected $primaryKey = 'exam_main_id';
    public $timestamps = false;

    protected $fillable = [
        'exam_main_no',
        'exam_main_type',
        'exam_main_model',
        'exam_main_tiers',
        'exam_main_service',
        'exam_main_notification',
        'exam_main_notifdate',
        'exam_main_name',
        'exam_main_nametamil',
        'exam_main_postname',
        'exam_main_lastdate',
        'exam_main_startdate',
        'exam_main_createdat',
        'exam_main_flag',
    ];

    protected $casts = [
        'exam_main_createdat' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->exam_main_createdat = now();
        });
    }

    /**
     * Accessor for a displayable name of the exam.
     */
    public function getDisplayNameAttribute()
    {
        return $this->exam_main_name; // or customize based on your requirements
    }

    /**
     * Example relationship (if there are related tables, update accordingly).
     */
    // public function relatedData()
    // {
    //     // Replace `RelatedModel` and foreign key based on your database structure.
    //     return $this->hasMany(RelatedModel::class, 'foreign_key', 'exam_main_id');
    // }
}
