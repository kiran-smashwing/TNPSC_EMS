<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function getDisplayNameAttribute()
    {
        return $this->name; // or whatever field you use for the name
    }
    public function getEmailDisplayAttribute()
    {
        return !empty($this->email) ? $this->email : 'No email available';
    }
    public function getProfileImageAttribute()
    {
        
        return '/assets/images/user/avatar-10.jpg';
    }
    /**
     * Ensure high-security measures for sw-admin role
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            // Ensure strong password requirements
            if (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&#]).{8,}$/', $user->password)) {
                throw new \Exception('Password must be at least 8 characters long and include at least one letter, one number, and one special character.');
            }
        });
    }

}
