<?php

namespace App\Http\Controllers;

use App\Models\VisitorType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VisitorsTypesController extends Controller
{


    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [

        ]);
        $success = false;

        if (!$validator->fails()) {
            VisitorType::updateOrCreate([
                'id' => $request->id
            ], [

            ]);

            $success = true;
        }

        return $this->success($success);
    }

    public function getList(Request $request)
    {

    }

    public function deleteOne(Request $request)
    {

    }
}
