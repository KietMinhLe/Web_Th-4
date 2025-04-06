<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $fillable = ['watch_id', 'size_name'];

    // Nhiều - Nhiều với Watch
    public function watch()
    {
        return $this->belongsTo(Watch::class);
    }

    // 1 - 1 với CartItem
    public function cartItem()
    {
        // return $this->hasOne(CartItem::class);
    }
}