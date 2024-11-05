<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $table = 'audits';
    
    protected $fillable = [
        'user_id',
        'role',
        'event',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'url',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'auditable_id' => 'integer',
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    public function auditable()
    {
        return $this->morphTo();
    }
}