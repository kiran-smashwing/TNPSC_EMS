<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    // Specify the table associated with the model if not using the default convention
    protected $table = 'audits';

    // Define which attributes are mass assignable
    protected $fillable = [
        'user_id',
        'event',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'url',
        'ip_address',
        'user_agent',
    ];

    // attributes that should be cast to a specific data type
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function auditable()
    {
        return $this->morphTo();
    }
}
