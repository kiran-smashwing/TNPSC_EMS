<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
    use HasFactory;

    protected $table = 'centers';
    protected $primaryKey = 'id';

    protected $fillable = [
        'image',
        'center_name',
        'center_code',
        'district_id',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function district()
    {
        return $this->belongsTo(Collectorate::class, 'district_id', 'id');
    }
}