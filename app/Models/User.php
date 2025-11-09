<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';  // <-- bảng bạn dùng user_id
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false;         // bảng không có created_at/updated_at

    protected $fillable = [
        'email','password','role','status','remember_token','created_at',
        'user_email','user_password','user_role','user_name', // nếu còn dùng bộ cũ ở chỗ nào
    ];

    protected $hidden = ['password','remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
