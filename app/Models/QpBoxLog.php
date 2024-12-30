<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QpBoxLog extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'ci_qp_box_log'; // Replace with your actual table name

    // Define the primary key (if it's not the default 'id')
    protected $primaryKey = 'id';

    // Define which fields are mass assignable
    protected $fillable = [
        'exam_id',
        'center_code',
        'hall_code',
        'ci_id',
        'exam_date',
        'qp_timing_log',
    ];

    // If you need to cast any columns to specific data types, e.g., JSON to array
    protected $casts = [
        'qp_timing_log' => 'array', // Ensures the qp_timing_log is treated as an array
    ];

    // Disable the timestamps if your table doesn't use them (optional)
    public $timestamps = true; // Change to false if not using timestamps
}
