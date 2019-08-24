<?php

namespace App\Http\Controllers;

use App\Http\Resources\News\NewsCollection;
use App\Http\Resources\News\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function getList(Request $request) {
        $news = News::with(['images', 'components', 'author', 'places', 'animals'])
            ->withCount('comments')
            ->getList($request)
            ->limit(10)
            ->get();

        return new NewsCollection($news);
    }

    public function getOne(Request $request) {
        $news = News::with(['images', 'components', 'author', 'places', 'animals'])
            ->find($request->id);

        return new NewsResource($news);
    }


}
