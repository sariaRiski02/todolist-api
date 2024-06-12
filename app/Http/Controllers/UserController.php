<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Casts\Json;

class UserController extends Controller
{

    public function register(RegisterUserRequest $request)
    {
        $data = $request->validated();
        $user = new User($data);
        $user->save();
        return new UserResource($user);
    }

    public function login(UserLoginRequest $request)
    {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();

        if (!$user || !password_verify($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user->token = Str::uuid();
        $user->save();

        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request, string $id)
    {
        $data = $request->validated();

        $auth = Auth::user();
        $user = User::find($id);

        if (!$user || $auth->id !== $user->id) {
            return response()->json([
                'error' => 'Data not found'
            ], 401);
        }
        $user->update($data);
        return new UserResource($user);
    }

    public function logout(string $id)
    {
        $auth = Auth::user();
        $user = User::find($id);

        if (!$user || $auth->id !== $user->id) {
            return response()->json([
                'error' => 'Data not found'
            ], 401);
        }
        $auth->token = null;
        if ($auth instanceof User) {
            $auth->save();
        }


        return response()->json([
            "data" => true
        ]);
    }
}
