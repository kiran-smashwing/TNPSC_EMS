<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamTrunkBoxOTLData extends Model
{
    use HasFactory;

    // Specify the table name (if it doesn't follow Laravel's naming convention)
    protected $table = 'exam_trunkbox_otl_data';

    // Specify the primary key (if it doesn't follow Laravel's naming convention)
    protected $primaryKey = 'id';

    // Disable timestamps if not using `created_at` and `updated_at`
    public $timestamps = true;

    // Specify the fillable fields for mass assignment
    protected $fillable = [
        'exam_id',
        'district_code',
        'center_code',
        'venue_code',
        'hall_code',
        'exam_date',
        'trunkbox_qr_code',
        'otl_code',
    ];

    // Define the casts for specific fields
    protected $casts = [
        'exam_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    // Define the relationships
    public function district()
    {
        return $this->belongsTo(District::class, 'district_code', 'district_code');
    }
    public function center()
    {
        return $this->belongsTo(Center::class, 'center_code', 'center_code');
    }
    public function venue()
    {
        return $this->belongsTo(Venues::class, 'venue_code', 'venue_code');
    }
}
