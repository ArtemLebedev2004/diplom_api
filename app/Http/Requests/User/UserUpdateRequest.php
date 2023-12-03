<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(Request $request): array
    {
        if (!$request->get('role_id')) {
            return [
                'fullName' => 'required|string|min:3',
                'email' => 'required|string|email|unique:users',
                'phone' => 'required|string|min:3|unique:users',
            ];
        } else {
            return [];
        }
    }
}
