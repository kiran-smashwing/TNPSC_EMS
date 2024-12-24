<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CIChecklist extends Model
{
    use HasFactory;

    // Define the table name (optional if following Laravel conventions)
    protected $table = 'ci_checklist';
    public $timestamps = false;

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
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->ci_checklist_createdat = now();
        });
    }
    // If you don't want to use the `updated_at` column, you can disable it
    public function getUpdatedAtColumn()
    {
        return null;
    }
}
