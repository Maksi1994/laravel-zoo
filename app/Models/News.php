<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Place;
use App\Models\Animal;

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

    public function animals() {
      return $this->morphToMany(Animal::class, 'taggable');
    }

}
