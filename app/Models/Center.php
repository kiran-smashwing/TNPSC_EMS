<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
    use HasFactory;

    protected $table = 'centers';
    protected $primaryKey = 'center_id';

    protected $fillable = [
        'image',
        'center_name',
        'center_code',
        'center_district_id',
        'status',
        'center_createdat',
        'center_image',
        'updated_by',
    ];

    protected $casts = [
        'status' => 'string',
    ];
    public function getUpdatedAtColumn()
    {
        return null;
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'center_district_id', 'district_id');
    }
}