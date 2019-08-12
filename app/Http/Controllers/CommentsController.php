<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Comment;
use App\Http\Resources\Comments\CommentsCollection;
use App\Models\{Animal, Place, News};

class CommentsController extends Controller
{
    public function save(Request $request) {
        $validator = Validator::make($request->all(), [
          'id' => 'exists:comments',
          'type_id' => 'required|numeric',
          'type' => 'required|in:news,animal,place',
          'body' => 'required|min:1',
        ]);
        $success = false;

        if (!$validator->fails()) {
            $success =  Comment::saveOne($request);
        }

        return $this->success($success);
    }

    public function getList(Request $request) {
      $validator = Validator::make($request->all(), [
        'id' => 'required|numeric',
        'type' => 'required|in:news,animal,place',
        'page' => 'numeric'
      ]);
      $model = null;
      $comments = null;

      if (!$validator->fails()) {
        switch ($request->type) {
          case 'news':
            $model = News::find($request->id);
            break;
          case 'animal':
            $model = Animal::find($request->id);
            break;
          case 'place':
            $model = Place::find($request->id);
            break;
        }

        if ($model) {
           $comments = $model->comments()
           ->with(['author', 'files'])
           ->paginate(20, '*', null, $request->page ?? 1);
        } else {
           return $this->success(false);
        }

        return new CommentsCollection($comments);
      }

      return $this->success(false);
    }

    public function delete(Request $request) {
      $success = false;
      $comment = Comment::find($request->id);

      if (Gate::allows('edit', $comment)) {
          $comment::destroy($request->id);
          $success = true;
      }

      return $this->success($success);
    }
}
