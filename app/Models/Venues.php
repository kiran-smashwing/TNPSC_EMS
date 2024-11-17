<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Venues extends Authenticatable
{
    use HasFactory;

    protected $table = 'venue'; // Specify the table name
    protected $primaryKey = 'venue_id'; // Primary key for the table
    public $incrementing = false; // Assuming 'venue_id' is not auto-incrementing, change to true if it is
    public $timestamps = false; // Disable Laravel's timestamps (created_at, updated_at)

    protected $fillable = [
        'venue_district_id',
        'venue_center_id',
        'venue_name',
        'venue_code',
        'venue_codeprovider',
        'venue_email',
        'venue_phone',
        'venue_alternative_phone',
        'venue_type',
        'venue_category',
        'venue_website',
        'venue_password',
        'venue_address',
        'venue_distance_railway',
        'venue_treasury_office',
        'venue_longitude',
        'venue_latitude',
        'venue_bank_name',
        'venue_account_name',
        'venue_account_number',
        'venue_branch_name',
        'venue_account_type',
        'venue_ifsc',
        'venue_createdat',
        'venue_image',
        'venue_status',
    ];

    protected $casts = [
        'venue_createdat' => 'datetime',
    ];

    public function getAuthPassword()
    {
        return $this->venue_password;
    }

    // Define the relationship with District (if applicable)
    public function district()
    {
        return $this->belongsTo(District::class, 'venue_district_id', 'district_id');
    }

    // Define the relationship with Center (if applicable)
    public function center()
    {
        return $this->belongsTo(Center::class, 'venue_center_id', 'center_id');
    }
}
