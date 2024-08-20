<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $table = 'designation';
    protected $fillable = [
        'name',
        'status',
        'created_by',
        'modified_by',
    ];

    // Define relationship to the User model
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modifier()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }
}
