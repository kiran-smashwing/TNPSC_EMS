<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreasuryOfficer extends Model
{
    use HasFactory;

    protected $table = 'treasury_officer';

    protected $fillable = [
        'image',
        'name',
        'employee_id',
        'role',
        'district_name',
        'mail',
        'phone',
        'mail_verify_status',
        'password',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'mail_verify_status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function collectorate()
    {
        return $this->belongsTo(Collectorate::class, 'district_name', 'district_name');
    }
}
