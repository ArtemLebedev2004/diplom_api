<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use App\Services\ImageService;
use Exception;

class UserController extends Controller
{
    public function index() {
        $users = User::with('role')->get();

        return response()->json($users, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {

            $user = User::findOrFail($id);

            return response()->json([
                'user' => $user
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Smth went erong in UserController.show'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $id)
    {
        try {

            $user = User::findOrFail($id);

            if (!$request->get('role_id')) {
                if ($request->hasFile('image')) {
                    (new ImageService)->updateImage($user, $request, '/images/users/', 'update');
                }

                $user->fullName = $request->fullName;
                $user->email = $request->email;
                $user->phone = $request->phone;
            } else {
                $user->role_id = $request->get('role_id');
            }

            $user->save();

            return response()->json("User details updated", 200);

        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Smth went erong in UserController.show'
            ]);
        }
    }
}
