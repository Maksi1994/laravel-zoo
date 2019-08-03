<?php

namespace App\Http\Backend\Controllers;

use App\Models\VisitorType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VisitorsTypesController extends Controller
{

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'price' => 'reqiired|numeric',
            'name' => 'required|unique:visitors_types',
            'period' => 'required',
            'age_limitation' => 'numeric'
        ]);
        $success = false;

        if (!$validator->fails()) {
            VisitorType::updateOrCreate([
                'id' => $request->id
            ], $request->all());

            $success = true;
        }

        return $this->success($success);
    }

    public function getOne(Requet $request)
    {

    }

    public function getList(Request $request)
    {

    }

    public function deleteOne(Request $request)
    {
        $success = (boolean)VisitorType::destroy($request->id);

        return $this->success($success);
    }
}
