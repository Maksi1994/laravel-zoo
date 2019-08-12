<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Place;
use App\Models\Animal;
use App\Models\Estimate;
use App\Models\Comment;

class News extends Model
{
    protected $guarded = [];
    public $timestamps = true;

    public function images() {
      return $this->morphMany(Image::class, 'imageable');
    }

    public function author() {
      return $this->belongsTo(User::class);
    }

    public function places() {
      return $this->morphToMany(Place::class, 'taggable');
    }

    public function estimates() {
        return $this->morphMany(Estimate::class, 'estimable');
    }

    public function animals() {
      return $this->morphToMany(Animal::class, 'taggable');
    }

    public function comments()
    {
      return $this->morphMany(Comment::class, 'commentable');
    }

    public function scopeGetList($query, Request $requst) {

    }

}
