<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileTeamStaffs extends Model
{
    use HasFactory;

    protected $table = 'mobile_team'; // Specify the table name
    protected $primaryKey = 'mobile_id'; // Primary key for the table

    protected $fillable = [
        'mobile_district_id',
        'mobile_name',
        'mobile_designation',
        'mobile_phone',
        'mobile_email',
        'mobile_employeeid',
        'mobile_password',
        'mobile_image', // Assuming this is where the image is stored
    ];

    protected $casts = [
        'mobile_createdat' => 'datetime',
    ];

    // Disable updated_at column management
    public function getUpdatedAtColumn()
    {
        return null;
    }

    // Define the relationship with District
    public function district()
    {
        return $this->belongsTo(District::class, 'mobile_district_id', 'district_id');
    }
}
