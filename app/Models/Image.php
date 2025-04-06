<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['watches_id', 'path_image'];

    public function watch()
    {
        return $this->belongsTo(Watch::class);
    }
}