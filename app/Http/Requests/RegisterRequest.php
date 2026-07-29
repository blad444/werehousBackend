<?php

namespace App\Http\Requests;

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
            "full_name" => "required|min:6|max:25|regex:/^[а-яА-Я\s]+$/u",
            'phone' => 'required|regex:/^\+7\s*\(\d{3}\)\s*\d{3}[-\s]\d{2}[-\s]\d{2}$/|unique:users,phone',
            "email" => "required|email|max:255|unique:users,email",
            "password" => "required|string|min:6",
        ];
    }
}
