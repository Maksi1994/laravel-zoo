<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\File;
use Illuminate\Http\Request;
use App\Models\User;

class Comment extends Model
{
   public $timestamps = true;
   public $guarded = [];

    public function commentable() {
      return $this->morphTo();
    }

    public function files() {
      return $this->morphMany(File::class, 'contentable');
    }

    public function author() {
      return $this->belongsTo(User::class, 'author');
    }

    public static function saveOne(Request $request) {
      $files = $request->allFiles();
      $model = null;
      $success = false;

      switch ($request->type) {
        case 'news':
          $model = News::find($request->type_id);
          break;
        case 'animal':
          $model = Animal::find($request->type_id);
          break;
        case 'place':
          $model = Place::find($request->type_id);
          break;
      }

      $request->merge(['author' => $request->user()->id]);

      if ($model) {
        $model->comments()->updateOrCreate([
          'id' => $request->id
        ],[
          'title' => $request->title,
          'body' => $request->body,
          'author' => $request->author
        ]);
        $success = true;
      }

      return $success;
    }
}
