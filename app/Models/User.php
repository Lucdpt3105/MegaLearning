<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;



class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    // dùng bảng mặc định 'users' và PK mặc định 'id' -> KHÔNG cần khai báo gì thêm

    protected $fillable = [
        'name', 'email', 'password', 'role', 'status', 'remember_token',
    ];
    protected $casts = ['password' => 'hashed'];
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}





