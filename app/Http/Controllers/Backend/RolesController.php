<?php

namespace App\Http\Controllers\Backend;

use App\Http\Resources\Roles\RoleCollection;
use App\Http\Resources\Roles\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
    public function save(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'exists:roles',
            'name' => 'required',
            'description' => 'required|min:3'
        ]);
        $success = false;

        if (!$validation->fails()) {
            Role::updateOrCreate([
                'id' => $request->id
            ], [
                'name' => $request->name,
                'description' => $request->description
            ]);
            $success = true;
        }

        return $this->success($success);
    }

    public function getOne(Request $request)
    {
        $role = Role::with(['users' => function($q) {
            $q->limit(10);
        }])->withCount(['users'])
            ->find($request->id);

        return new RoleResource($role);
    }

    public function getList(Request $request)
    {
        $roles = Role::withCount('users')
            ->getList($request)
            ->paginate(20, null, '*', $request->page ?? 1);

        return new RoleCollection($roles);
    }

    public function delete(Request $request)
    {
        $success = (boolean)Role::destroy($request->id);

        return $this->success($success);
    }


}
