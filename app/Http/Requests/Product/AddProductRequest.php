<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class AddProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|unique:products',
            'description' => 'required',
            'amount' => 'required|numeric',
            'type' => 'required',
            'date' => 'required|date_format:d/m/Y',
            'photo' => 'required|mimes:png,jpg,jpeg'
        ];
    }
}
