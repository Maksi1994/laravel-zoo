<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Place;
use App\Models\Animal;
use App\Models\Estimate;
use App\Models\Comment;
use Illuminate\Http\Request;

class News extends Model
{
    protected $guarded = [];
    public $timestamps = true;

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function places()
    {
        return $this->morphedByMany(Place::class, 'taggable');
    }

    public function estimates()
    {
        return $this->morphMany(Estimate::class, 'estimable');
    }

    public function animals()
    {
        return $this->morphedByMany(Animal::class, 'taggable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function scopeGetList($query, Request $request)
    {
        $query->when($request->type === 'new', function ($q) {
            $q->orderBy('created_at', 'desc');
        });

        $query->when($request->type === 'popular', function ($q) use ($request) {
            switch ($request->orderType) {
                case 'estimate':
                    $q->orderBy('ex', 'desc');
                    break;
                case 'comments':
                    $q->orderBy('comments', 'desc');
            }

        });

        return $query;
    }

}
