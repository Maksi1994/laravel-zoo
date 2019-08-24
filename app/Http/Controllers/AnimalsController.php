<?php

namespace App\Http\Controllers;

use App\Http\Resources\Animals\AnimalResource;
use App\Http\Resources\Animals\AnimalsCollection;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AnimalsController extends Controller
{

    public function getList(Request $request)
    {

        $animals = Animal::with(['images', 'place', 'estimates'])
            ->withCount([
                'estimates as estimate' => function ($q) {
                    $q->select(DB::raw('ROUND(AVG(estimates.estimate), 2)'));
                }, 'comments'])
            ->getFrontendList($request)
            ->paginate(10, null, '*', $request->page ?? 1);

        return new AnimalsCollection($animals);
    }

    public function getOne(Request $request)
    {
        $animal = Animal::with([
            'images',
            'place',
        ])->withCount(['estimates', 'comments'])->find($request->id);

        return new AnimalResource($animal);
    }
}
