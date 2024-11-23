<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamService extends Model
{
    use HasFactory;

    // Define the table name (optional if following Laravel conventions)
    protected $table = 'exam_service';
    protected $casts = [
        'examservice_status' => 'boolean',
    ];

    // Define the primary key
    protected $primaryKey = 'examservice_id';
    public $timestamps = false;


    // Define the fillable attributes
    protected $fillable = [
        'examservice_name',      
        'examservice_code',      
        'examservice_createdat', 
        'examservice_status'
    ];

    // Add timestamp for createdat
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->examservice_createdat = now();
        });
    }

    // Define the relationship with the Currentexam model
    public function currentexam()
    {
        return $this->hasMany(Currentexam::class, 'exam_main_service', 'examservice_id');
    }

}
