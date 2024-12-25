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
        $this->belongsTo(User::class, "user_id");
    }
    
    public function catagory () {
        $this->belongsTo(Catagory::class);
    }

    public function likes () {
        $this->hasMany(Like::class);
    }

    public function comments () {
        $this->hasMany(Comment::class);
    }

    public function images () {
        $this->morphMany(Image::class, 'imageable');
    }
}
