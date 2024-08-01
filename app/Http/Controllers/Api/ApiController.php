<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
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

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            return $this->success([
                'token' => $user->createToken("API TOKEN")->plainTextToken
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
        $userData = auth()->user();
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
