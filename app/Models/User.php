<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Http\Request;
use App\Models\Comment;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function image() {
        return $this->morphOne(Image::class, 'imageble');
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function comments() {
      return $this->hasMany(Comment::class, 'author');
    }

    public function hasRole($role) {
        return $this->role()->where('name', $role)->exists();
    }

}
