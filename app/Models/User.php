<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = ['user_name', 'password', 'phone', 'email', 'email_verified_at'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Mã hóa mật khẩu trước khi lưu
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    // Chuyển email về lowercase
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    // Quan hệ 1-Nhiều với UserAddress
    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    // Quan hệ 1-Nhiều với Order
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}