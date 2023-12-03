<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fullName' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|min:12|max:12|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ];
    }
}
