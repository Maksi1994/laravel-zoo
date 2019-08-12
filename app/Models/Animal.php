<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Estimate;
use App\Models\Comment;

class Animal extends Model
{

    protected $guarded = [];
    public $timestamps = true;


    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function place() {
        return $this->hasOne(Place::class);
    }

    public function tags()
    {
        return $this->morphToMany(News::class, 'taggable');
    }

    public function estimates() {
        return $this->morphMany(Estimate::class, 'estimable');
    }

    public function comments() {
      return $this->morphMany(Comment::class, 'commentable');
    }

    public static function saveOne(Request $request)
    {

        $animalModel = self::updateOrCreate([
          'id' => $request->id
        ], $request->all());
        /*
        $images = [];

        if (!empty($request->allFiles())) {
            foreach ($request->allFiles() as $imageFile) {

            }

            $animalModel->images()->createmMany($images);
        }

        if (!empty($request->removedImages)) {
            $imagesForDeleting = $animalModel->images()->whereIn('id', $request->removedImages);

            foreach ($imagesForDeleting as $image) {
                $image->delete();
            }
        }
        */
    }
}
