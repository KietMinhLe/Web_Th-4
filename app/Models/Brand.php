<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brands';

    protected $fillable = ['brand_name', 'description', 'status'];

    public function watches()
    {
        return $this->hasMany(Watch::class);
    }
}