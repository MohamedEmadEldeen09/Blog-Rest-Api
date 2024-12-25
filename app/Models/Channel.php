<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    /** @use HasFactory<\Database\Factories\ChannelFactory> */
    use HasFactory;

    public function owner () {
        $this->hasOne(User::class, 'user_id');
    }

    public function subscribers () {
        $this->belongsToMany(User::class)->withTimestamps();
    }
}
