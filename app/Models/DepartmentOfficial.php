<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class DepartmentOfficial extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'department_officer'; // Your table name
    protected $primaryKey = 'dept_off_id'; // Your primary key field

    protected $fillable = [
        'dept_off_name',
        'dept_off_designation',
        'dept_off_phone',
        'dept_off_role',
        'dept_off_emp_id',
        'dept_off_email',
        'dept_off_password',
        'dept_off_image',
    ];

    protected $hidden = [
        'dept_off_password', // Hide the password when retrieving data
    ];

    protected $casts = [
        'dept_off_createdat' => 'datetime', // Assuming this field is a timestamp
    ];

    // Custom method to hide updated_at column
    public function getUpdatedAtColumn()
    {
        return null; // If you don't want to track updates (like in the `Center` model)
    }

     // You can add other relationships like 'role' if you have a related model for roles
    public function role()
    {
        return $this->belongsTo(Role::class, 'dept_off_role', 'role_id'); // Assuming you have a 'Role' model
    }
}
