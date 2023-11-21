<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {
        
        try {

            $user = User::create([
                'fio' => $request->input('fio'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'password' => Hash::make($request->input('password'))
            ]);

            $token = $user->createToken('user_token')->plainTextToken;
            
            return response()->json([
                'user' => $user,
                'token' => $token
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Smth went wrong in AuthController.register'
            ]);
        }
        
    }


    public function login(LoginRequest $request) {

        try {
            
            $user = User::where('email', '=', $request->input('email'))->firstOrFail();

            if (!Hash::check($request->input('password'), $user->password)) {
                return response()->json([
                    'message' => 'Wrong password'
                ]);
            }

            $token = $user->createToken('user_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Smth went wrong in AuthController.login'
            ]);
        }

    }


    public function logout(LogoutRequest $request) {

        try {

            $user = User::findOrFail($request->input('user_id'));

            if (!count($user->tokens()->get())) {
                return response()->json('NO', 200);
            }

            $user->tokens()->delete();

            return response()->json('User logged out!', 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Smth went wrong in  AuthController.logout'
            ]);
        }

    }
}
