<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class EditProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        if ($request->file('photo')) {
            return [
                'title' => 'required',
                'description' => 'required',
                'amount' => 'required|numeric',
                'type' => 'required',
                'date' => 'required|date_format:d/m/Y',
                'photo' => 'required|mimes:png,jpg,jpeg',
            ];
        } else {
            return [
                'title' => 'required',
                'description' => 'required',
                'amount' => 'required|numeric',
                'type' => 'required',
                'date' => 'required|date_format:d/m/Y',
                'photo' => 'required',
            ];
        }

    }
}
