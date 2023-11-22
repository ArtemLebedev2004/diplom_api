<?php

namespace App\Policies;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class AdminPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function admin(User $user) {
        if ($user->role_id != 1) {

            return response()->json([
                "warning" => [
                    "code" => 403,
                    "message" => "Доступ для вашей группы зпрещён"
                ]
            ], 403);

        } else {
            return true;
        }
    }
}
