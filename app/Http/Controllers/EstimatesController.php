<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\{Animal, News, Place};

class EstimatesController extends Controller
{
    public function saveEstimate(Request $request) {
      $validation = Validator::make($request->all(), [
        'type' => 'required|min:1|in:news,animal,place',
        'estimate' => 'required|numeric|between:1,5',
        'id' => 'required|numeric'
      ]);

      $userId = $request->user()->id;
      $hasEstimate = null;
      $model = null;
      $success = false;

      if (!$validation->fails()) {
          switch ($request->type) {
            case 'news':
              $model = News::find($request->id);
              break;
            case 'animal':
              $model = Animal::find($request->id);
              break;
            case 'place':
              $model = Place::find($request->id);
          }


          if (!empty($model)) {
            $hasEstimate = $model->estimates()->where('user_id', $userId)->exists();

            if (!$hasEstimate) {
                $model->estimates()->where('user_id', $userId)->create([
                  'user_id' => $userId,
                  'estimate' => $request->estimate
                ]);
              } else {
                $model->estimates()->where('user_id', $userId)->delete();
              }

              $success = true;
            }
      }

      return $this->success($success);
    }
}
