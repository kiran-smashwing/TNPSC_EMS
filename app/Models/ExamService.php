<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamService extends Model
{
    use HasFactory;

    // Define the table name (optional if following Laravel conventions)
    protected $table = 'exam_service';

    // Define the primary key
    protected $primaryKey = 'examservice_id';

    // Define the fillable attributes
    protected $fillable = [
        'examservice_name',       // Name of the exam service
        'examservice_code',       // Code for the exam service
        'examservice_createdat',  // Created at timestamp
    ];

    // Specify that `examservice_createdat` is a timestamp field
    protected $casts = [
        'examservice_createdat' => 'datetime',
    ];

    // If you don't want to use the `updated_at` column, you can disable it
    public function getUpdatedAtColumn()
    {
        return null;
    }
}
