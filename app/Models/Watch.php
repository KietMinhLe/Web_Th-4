<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Watch extends Model
{
    use HasFactory;

    protected $primaryKey = "id";

    protected $fillable = ['product_name', 'description', 'brand_id', 'price', 'status', 'discount', 'quantity'];


    public function images()
    {
        return $this->hasMany(Image::class);
    }


    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function sizes()
    {
        return $this->hasMany(Size::class);
    }
}