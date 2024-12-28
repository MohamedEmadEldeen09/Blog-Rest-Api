<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    /** @use HasFactory<\Database\Factories\BlogFactory> */
    use HasFactory;

    protected $guarded = [];

    public function author () {
        return $this->belongsTo(User::class, "user_id");
    }
    
    public function catagory () {
        return $this->belongsTo(Catagory::class);
    }

    public function likes () {
        return $this->hasMany(Like::class);
    }

    public function comments () {
        return $this->hasMany(Comment::class);
    }

    public function images () {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function channel () {
        return $this->belongsTo(Channel::class);
    }
}
