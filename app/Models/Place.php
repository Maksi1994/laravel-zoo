<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Estimate;
use App\Models\Comment;

class Place extends Model
{
    public $timestamps = true;
    protected $guarded = [];

    public function animal()
    {
        return $this->hasOne(Animal::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function estimates() {
        return $this->morphMany(Estimate::class, 'estimable');
    }

    public static function saveOne(Request $request)
    {
        self::updateOrCreate([
          'id' => $request->id
        ], $request->all());
    }

    public function news()
    {
         return $this->morphToMany(News::Class, 'taggable');
    }

    public function comments()
    {
          return $this->morphMany(Comment::class, 'commentable');
    }

    public function scopeGetList($query, Request $request)
    {
        $query->when($request->orderType === 'new', function ($q) use ($request) {
            $q->orderBy('created_at', $request->order ?? 'desc');
        });

        $query->when($request->orderType === 'area', function ($q) use ($request) {
            $q->orderBy('area', $request->order ?? 'desc');
        });

        return $query;
    }
}
