<?php

namespace App\Http\Controllers\Backend;

use App\Http\Resources\Backend\Animals\AnimalsCollection;
use App\Http\Resources\Backend\Animals\AnimalsResource;
use App\Models\Animal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AnimalsController extends Controller
{

    public function save(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'exists:animals',
            'weight' => 'required|numeric',
            'age' => 'required|numeric',
            'name' => 'required',
            'place_id' => 'required|exists:places,id'
        ]);
        $success = false;

        if (!$validation->fails()) {
            Animal::saveOne($request);
            $success = true;
        }

        return $this->success($success);
    }

    public function getOne(Request $request)
    {
        $animal = Animal::with('place')->find($request->id);

        return new AnimalsResource($animal);
    }

    public function getList(Request $request)
    {
        $animals = Animal::with('places')
            ->getBackendList($request)
            ->paginate(20, null, null, $request->page ?? 1);

        return new AnimalsCollection($animals);
    }

    public function delete(Request $request)
    {
        $success = (boolean)Animal::destroy($request->id);

        return $this->success($success);
    }
}
