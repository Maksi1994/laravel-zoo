<?php

namespace App\Http\Controllers\Backend;

use App\Models\News;
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
<<<<<<< HEAD
        $news = News::with(['author', 'animals', 'places'])->find($request->id);

        return new NewsRessource($news);
=======
        $news = News::with(['images', 'author'])->find($request->id);

        return new NewsResoru
>>>>>>> 9c84c4742b8bb725aa54020a6275d3850d86e671
    }

    public function getList(Request $request) {
        $news = News::with(['author', 'animals', 'places'])
        ->getList($requst)
        ->paginate(20, null, null, $requst->page ?? 1);

        return new NewsCollection($news);
    }

    public function delete(Request $request) {
      $success = (boolean) News::destroy($request->id);

      return $this->success($success);
    }
}
