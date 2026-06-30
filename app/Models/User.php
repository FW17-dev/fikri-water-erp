<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\LogsActivity;

class User extends Authenticatable
{
    use HasFactory, Notifiable, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Mencatat login
    public static function logLogin($userId)
    {
        AuditLog::create([
            'user_id' => $userId,
            'action' => 'login',
            'model' => 'User',
            'description' => 'User login',
        ]);
    }

    // Mencatat logout
    public static function logLogout($userId)
    {
        AuditLog::create([
            'user_id' => $userId,
            'action' => 'logout',
            'model' => 'User',
            'description' => 'User logout',
        ]);
    }
}