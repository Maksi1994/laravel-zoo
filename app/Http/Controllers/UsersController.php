<?php

namespace App\Http\Controllers;

use App\Http\Resources\Roles\RoleCollection;
use App\Http\Resources\Users\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Notifications\Registration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    public function regist(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role_id' => 'required|exists:roles,id'
        ]);
        $success = false;

        if (!$validation->fails()) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'activate_token' => Str::random(25),
                'role_id' => $request->role_id
            ]);

            $user->notify(new Registration($user));

            if ($request->hasFile('avatar')) {
                //  $user->image()->c
            }

            $success = true;
        }

        return $this->success($success);
    }

    public function updateOne(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'required|exists:users',
            'name' => 'required|min:3',
            'role_id' => 'exists:roles,id'
        ]);
        $success = false;

        if (!$validation->fails()) {
            $user = User::where('id', $request->id)->update([
                'name' => $request->name,
                'role_id' => $request->role_id
            ]);

            if ($request->hasFile('avatar')) {
                $user->image()->delete();
                $user->image()->create();
            }

            $success = true;
        }

        return $this->success($success);
    }

    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'password' => 'required|min:3',
            'remember_me' => 'boolean'
        ]);
        $success = false;

        if (!$validation->fails()) {
            $isSuccessAuth = Auth::attempt([
                'email' => $request->email,
                'password' => $request->password
            ]);
            $userModel = $request->user();
            $isActiveAccount = $userModel->where('active', 1)->exists();
            $newToken = $userModel->createToken('Personal Token');

            $token = $newToken->token;
            $token->expires_at = Carbon::now()->addWeek(1);
            $token->save();

            if ($isSuccessAuth && $isActiveAccount) {
                if ($request->remember_me) {
                    $token->expires_at = Carbon::now()->addYears(1);
                    $token->save();
                }

                $success = true;
            }
        }

        if (!$success) {
            return $this->success(false);
        }

        return response()->json([
            'access_token' => $newToken->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $newToken->token->expires_at
            )->toDateTimeString()
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return $this->success(true);
    }

    public function activateUser(Request $request)
    {
        $userModel = User::where('activate_token', $request->token)->first();

        if ($userModel) {
            $userModel->active = 1;
            $userModel->activate_token = null;
            $userModel->save();
        }

        return response()->redirectTo('/');
    }

    public function getAllRoles(Request $request)
    {
        $roles = Role::all();

        return new RoleCollection($roles);
    }

    public function isUsedEmail(Request $request)
    {
        $isUser = User::where('email', $request->email)->exists();

        return $this->success($isUser);
    }

    public function isAdmin(Request $request)
    {
        if (empty(($request->user()->role()->exists()))) {
            return $this->success(false);
        } else {
            return $this->success($request->user()->role()->name === 'Admin');
        }
    }

    public function getList(Request $request)
    {
        $users = User::with(['image', 'role'])
            ->orderBy('created_at', $request->order ?? 'desc')
            ->paginate(20, null, null, $request->page ?? 1);

        return response()->json($users);
    }

    public function getOne(Request $request)
    {
        $user = User::find($request->id);

        if (!$user) {
            return $this->success(false);
        }

        return new UserResource($user);
    }

    public function getCurrUser(Request $request)
    {
        return new UserResource($request->user()->with('role')->first());
    }
}
