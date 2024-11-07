<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CiChecklist extends Model
{
    use HasFactory;

    // Define the table name (optional if following Laravel conventions)
    protected $table = 'ci_checklist';

    // Define the primary key
    protected $primaryKey = 'ci_checklist_id';

    // Define the fillable attributes
    protected $fillable = [
        'ci_checklist_examid',        // Exam ID associated with the checklist
        'ci_checklist_type',          // Type of checklist (e.g., document, process, etc.)
        'ci_checklist_description',   // Optional description of the checklist item
        'ci_checklist_createdat',     // Created at timestamp
    ];

    // Specify that `ci_checklist_createdat` is a timestamp field
    protected $casts = [
        'ci_checklist_createdat' => 'datetime',
    ];

    // If you don't want to use the `updated_at` column, you can disable it
    public function getUpdatedAtColumn()
    {
        return null;
    }

    // If there are relationships with other models, you can define them here
    // For example, if a checklist belongs to an exam, you could define a relationship method.
    // public function exam()
    // {
    //     return $this->belongsTo(Exam::class, 'ci_checklist_examid', 'exam_id');
    // }
}
