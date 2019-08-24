<?php

namespace App\Http\Controllers;

use App\Http\Resources\Backend\Places\PlaceResource;
use App\Http\Resources\Places\PlacesCollection;
use App\Models\Place;
use Illuminate\Http\Request;

class PlacesController extends Controller
{
    public function getOne(Request $request)
    {
        $place = Place::with(['images', 'tags'])
            ->withCount([
                'news',
                'animals',
                'estimates as estimate' => function ($q) {
                    $q->select(DB::raw('ROUND(AVG(estimates.estimate), 2)'));
                }])
            ->find($request->id);

        return new PlaceResource($place);
    }

    public function getList(Request $request)
    {
        $places = Place::with(['images', 'tags'])
            ->withCount(['news', 'animals', 'images'])
            ->getList($request)
            ->paginate(20, null, '*', $request->page ?? 1);

        return new PlacesCollection($places);
    }
}
