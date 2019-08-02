<?php

namespace App\Http\Controllers;

use App\Http\Resources\Visitors\VisitorResource;
use App\Http\Resources\Visitors\VisitorsCollection;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VisitorsController extends Controller
{
    public function save(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id' => 'exists:visitors',
            'name' => 'required',
            'type_id' => 'exists:visitors_types,id',
            'date' => 'required'
        ]);
        $success = false;

        if (!$validate->fails()) {
            Visitor::updateOrCreate([
                'id' => $request->id
            ], [
                'name' => $request->name,
                'type_id' => $request->type_id,
                'date' => $request->date
            ]);

            $success = true;
        }

        return $this->success($success);
    }

    public function getList(Request $request)
    {
        $visitors = Visitor::with('type')
            ->getList($request)
            ->paginate(20, '*', null, $request->page ?? 1);

        return new VisitorsCollection($visitors);
    }

    public function getOne(Request $request)
    {
        $visitor = Visitor::with('type')->find($request->id);

        return new VisitorResource($visitor);
    }

    public function delete(Request $request)
    {
        $success = (boolean)Visitor::destroy($request->id);

        return $this->success($success);
    }
}
