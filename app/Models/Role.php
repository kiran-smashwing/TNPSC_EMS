<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'role';
    protected $primaryKey = 'role_id';

    protected $fillable = [
        'role_department',
        'role_name',
        'role_createdat',
    ];

    public $timestamps = false;

    protected $casts = [
        'role_createdat' => 'datetime',
    ];
}
