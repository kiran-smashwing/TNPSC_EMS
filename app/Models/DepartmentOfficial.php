<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class DepartmentOfficial extends Authenticatable
{

    use HasFactory;
    protected $table = 'department_officer'; // Your table name
    protected $primaryKey = 'dept_off_id'; // Your primary key field
    protected $casts = [
        'dept_off_status' => 'boolean',
        'dept_off_email_status' => 'boolean',
    ];
    public $timestamps = false;
    protected $fillable = [
        'dept_off_name',
        'dept_off_designation',
        'dept_off_phone',
        'dept_off_role',
        'dept_off_emp_id',
        'dept_off_email',
        'dept_off_password',
        'dept_off_image',
        'dept_off_status',
        'dept_off_email_status',
        'remember_token',
        'dept_off_createdat'
    ];
    // Add timestamp for createdat
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->dept_off_createdat = now();
        });
    }
    public function getDisplayNameAttribute()
    {
        return $this->dept_off_name; // or whatever field you use for the name
    }

    protected $hidden = [
        'dept_off_password', // Hide the password when retrieving data
    ];

    public function getAuthPassword()
    {
        return $this->dept_off_password;
    }

    // You can add other relationships like 'role' if you have a related model for roles
    public function role()
    {
        return $this->belongsTo(Role::class, 'dept_off_role', 'role_id'); // Assuming you have a 'Role' model
    }
}
