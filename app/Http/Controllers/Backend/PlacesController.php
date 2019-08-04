<?php

namespace App\Http\Controllers\Backend;

use App\Http\Resources\Backend\Places\PlaceResource;
use App\Http\Resources\Backend\Places\PlacesCollection;
use App\Models\Place;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PlacesController extends Controller
{
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'exists:places',
            'name' => 'required',
            'area' => 'numeric'
        ]);
        $success = false;

        if (!$validator->fails()) {
            Place::saveOne($request);
            $success = true;
        }

        return $this->success($success);
    }

    public function getList(Request $request)
    {
        $places = Place::with('images')
            ->getList($request)
            ->paginate(20, null, null, $request->page ?? 1);

        return new PlacesCollection($places);
    }

    public function getOne(Request $request)
    {
        $place = Place::with('images')->find($request->id);

        return new PlaceResource($place);
    }

    public function delete(Request $request)
    {
        $success = (boolean)Place::destroy($request->id);

        return $this->success($success);
    }
}
