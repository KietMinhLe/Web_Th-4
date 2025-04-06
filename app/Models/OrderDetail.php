<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = ['watches_id', 'order_id', 'quantity', 'price', 'discount'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function watch()
    {
        return $this->belongsTo(Watch::class);
    }
}