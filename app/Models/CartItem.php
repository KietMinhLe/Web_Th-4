<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id', 'watch_id', 'size_id', 'quantity', 'price'];

    // Quan hệ với Cart
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // Quan hệ với Watch
    public function watch()
    {
        return $this->belongsTo(Watch::class);
    }

    // Quan hệ với Size
    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}