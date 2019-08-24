<?php

namespace App\Http\Controllers\Backend;

use App\Models\VisitorType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Backend\VisitorsTypes\VisitorTypeResource;
use App\Http\Controllers\Controller;

class VisitorsTypesController extends Controller
{

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'exists:visitors_types',
            'price' => 'required|numeric|between:0,99.00',
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

    public function getOne(Request $request)
    {
        $visitorType = VisitorType::withCount('visitors')->find($request->id);

        return response()->json($visitorType);
    }

    public function getList(Request $request)
    {
        $visitorsTypes = VisitorType::withCount('visitors')
        ->getList($request)
        ->paginate(20, '*', null, $request->page ?? 1);

        return response()->json($visitorsTypes);
    }

    public function delete(Request $request)
    {
        $success = (boolean)VisitorType::destroy($request->id);

        return $this->success($success);
    }
}
