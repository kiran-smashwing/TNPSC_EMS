<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamAuditLog extends Model
{
    protected $fillable = [
        'exam_id',
        'user_id',
        'action_type',
        'task_type',
        'role',
        'department',
        'before_state',
        'after_state',
        'description',
        'metadata'
    ];

    protected $casts = [
        'before_state' => 'array',
        'after_state' => 'array',
        'metadata' => 'array',
    ];

 
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
}
