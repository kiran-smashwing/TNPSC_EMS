<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CIStaffAllocation extends Model
{
    protected $table = 'ci_staff_allocation'; // Explicitly define the table name (optional if it follows naming conventions)

    // Define the fillable columns (columns you can mass-assign)
    protected $fillable = [
        'exam_id',
        'exam_date',
        'ci_id',
        'invigilators',
        'assistants',
        'scribes'
    ];

    // Cast the JSON columns to arrays automatically
    protected $casts = [
        'invigilators' => 'array',
        'assistants' => 'array',
        'scribes' => 'array',
    ];

    // If you want to add additional logic to manipulate the model, you can define methods here
}
