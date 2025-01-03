<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * to distinguish this user whether he is an admin or a user 
     * through an API request.
     **/
    public function isAdmin (): bool {
        return false;
    }

    /* delete the user profile image when deleting the user */
    public function delete () {
        $this->image()->delete();
        return parent::delete();
    }

    public function ownChannels () {
        return $this->hasMany(Channel::class);
    }

    public function blogs () {
        return $this->hasMany(Blog::class);
    }

    public function image () {
        return $this->morphOne(Image::class, "imageable");
    }

    public function suscribedChannels () {
        return $this->belongsToMany(Channel::class)->withTimestamps();
    }

    public function likes () {
        return $this->hasMany(Like::class);
    }

    public function comments () {
        return $this->hasMany(Comment::class);
    }
}
