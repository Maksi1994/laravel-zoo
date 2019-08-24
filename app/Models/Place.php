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

    public function animals()
    {
        return $this->hasMany(Animal::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function tags()
    {
        return $this->morphToMany(News::class, 'taggable');
    }

    public function estimates()
    {
        return $this->morphMany(Estimate::class, 'estimable');
    }

    public static function saveOne(Request $request)
    {
        self::updateOrCreate([
            'id' => $request->id
        ], [
            'name' => $request->name,
            'area' => $request->area
        ]);
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
        $query->when($request->orderType === 'new' || $request->orderType !== 'popular', function ($q) use ($request) {
            $q->orderBy('created_at', $request->order ?? 'desc');
        });

        $query->when($request->orderType === 'popular', function ($q) use ($request) {
            $q->orderBy('animals_count', $request->order ?? 'desc');
        });

        return $query;
    }

    public function scopeGetFrontendList($query, Request $request)
    {
        $query->when($request->orderType === 'new', function ($q) use ($request) {
            $q->orderBy('created_at', $request->order ?? 'desc');
        });

        $query->when($request->orderType === 'popular', function ($q) use ($request) {
            $q->orderBy('animals_count', $request->order ?? 'desc');
        });

        $query->when($request->orderType === 'estimate', function ($q) use ($request) {
            $q->orderBy('estimate', $request->order ?? 'desc');
        });

        return $query;
    }
}
