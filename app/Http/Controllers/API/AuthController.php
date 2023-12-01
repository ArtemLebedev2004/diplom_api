<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {

        try {

            $user = User::create([
                'fio' => $request->input('fio'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'role_id' => 2
            ]);

            $token = $user->createToken('user_token')->plainTextToken;

            return response()->json([
                'content' => [
                    'user_token' => $token
                ]
            ], 201);

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
                    'warning' => [
                        'code' => 401,
                        'message' => 'Неудачный вход'
                    ]
                ], 401);
            }

            $token = $user->createToken('user_token')->plainTextToken;

            return response()->json([
                'content' => [
                    'user_token' => $token
                ]
            ], 200);

        } catch (\Exception $e) {
            if ($e instanceof ModelNotFoundException) {

                return response()->json([
                    'warning' => [
                        'code' => 401,
                        'message' => 'Неудачный вход'
                    ]
                ], 401);

            } else {

                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Smth went wrong in AuthController.login'
                ]);

            }

        }

    }


    public function logout() {

        try {

            $user = User::findOrFail(Auth::id());

            $user->tokens()->delete();

            return response()->json([
                'content' => [
                    'message' => 'Выход'
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Smth went wrong in  AuthController.logout'
            ]);
        }

    }
}
