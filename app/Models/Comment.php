<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    public function user () {
        $this->belongsTo(User::class);
    }

    public function blog () {
        $this->belongsTo(Blog::class);
    }
}
