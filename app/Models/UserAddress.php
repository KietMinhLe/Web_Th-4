<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'label', 'receive_name', 'receive_phone', 'is_default'];

    // Thiết lập quan hệ Nhiều-1 với User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
