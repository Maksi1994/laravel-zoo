<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsController extends Controller
{

    public function save(Request $request) {
      $validation = Validator::make($request->all(), [
        'id' => 'exists:news',
        'title' => 'required|min:3',
        'body' => 'required|min:30',
        'animals' => 'required|array',
        'places' => 'required|array',
        'animals.*' => 'required|exists,id',
        'places.*' => 'required|exists,id',
      ]);
      $success = false;

      if (!$validation->fails()) {
        $news = News::updateOrCreate([
          'id' => $request->id
        ], $request);

        $news->animals()->assync($request->animals || []);
        $news->places()->assync($request->places || []);

        $success = true;
      }

      return $this->success($success);
    }

    public function getOne(Request $request) {

    }

    public function getList(Request $request) {

    }

    public function delete(Request $request) {
      $success = (boolean) News::destroy($request->id);

      return $this->success($success);
    }
}
