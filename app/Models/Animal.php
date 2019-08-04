<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\News;

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
         return $this->morphToMany(News, 'taggable');
    }

    public static function saveOne(Request $request)
    {

        $animalModel = self::updateOrCreate($request);
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
    }
}
