<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertNotification extends Model
{
    use HasFactory;

    // Define the table name (optional if following Laravel convention)
    protected $table = 'alert_notifications';

    // Define the fillable fields to allow mass assignment
    protected $fillable = [
        'exam_id',
        'district_code',
        'center_code',
        'hall_code',
        'ci_id',
        'exam_date',
        'exam_session',
        'alert_type',
        'details',
        'remarks'
    ];

    // Define the fields to be treated as dates (timestamps)
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Optionally, you can define relationships if needed
    // public function exampleRelationship()
    // {
    //     return $this->belongsTo(ExampleModel::class);
    // }
}
