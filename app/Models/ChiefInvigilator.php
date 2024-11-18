<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ChiefInvigilator extends Authenticatable
{
    use HasFactory;

    protected $table = 'cheif_invigilator';
    protected $primaryKey = 'ci_id';
    public $timestamps = false;

    protected $fillable = [
        'ci_district_id',
        'ci_center_id',
        'ci_venue_id',
        'ci_name',
        'ci_email',
        'ci_phone',
        'ci_alternative_phone',
        'ci_designation',
        'ci_password',
        'ci_image',
    ];

    protected $hidden = [
        'ci_password',
    ];

    // Boot method to set `ci_createdat` when creating a new record
    public function getAuthPassword()
    {
        return $this->ci_password;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->ci_createdat = now()->format('H:i:s'); // Set creation time without time zone
        });
    }
}
