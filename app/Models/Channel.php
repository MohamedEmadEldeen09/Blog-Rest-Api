<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    /** @use HasFactory<\Database\Factories\ChannelFactory> */
    use HasFactory;

    public function owner () {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subscribers () {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function blogs () {
        return $this->hasMany(Blog::class);
    }
}
