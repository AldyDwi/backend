<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use HttpResponses;
    
    public function register(Request $request)
    {
        try 
        {
            $validateUser = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
            ]);

            if($validateUser->fails()){
                return $this->error('Validation error', 401, $validateUser->errors());
            }

            // $id = User::max('id') + 1;

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            // $userId = $id;
            $userId = $user->id;

            $roleUser = RoleUser::create([
                'user_id' => $userId,
                'role_id' => 2, 
            ]);

            return $this->success([
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'role' => $roleUser
            ], 'Registrasi sukses');

        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function login(Request $request) 
    {
        try
        {
            $validateUser = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if($validateUser->fails()){
                return $this->error('Validation error', 401, $validateUser->errors());
            }

            if(!Auth::attempt($request->only(['email', 'password']))) {
                return $this->error('Email atau password salah', 401);
            }

            $user = User::where('email',$request->email)->first();

            return $this->success([
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 'Login sukses');

        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function profile() {
        $user = auth()->user();

        $roles = $user->roles()->get()->map(function($role) {
            return [
                'id' => $role->pivot->role_id,
                'role' => $role->name
            ];
        });

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'level' => $roles,
            'email_verified_at' => $user->email_verified_at,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];

        return $this->success($userData, 'Informasi Profile');
    }

    public function logout(Request $request) {
        try{
            $request->user()->currentAccessToken()->delete();
            return $this->success([], 'User logged out');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
}
