<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'full_name' => 'required|string|min:6|max:255|regex:/^[а-яА-Я\s]+$/u',
            'phone' => [
                'required',
                'regex:/^\+7\s*\(\d{3}\)\s*\d{3}[-\s]\d{2}[-\s]\d{2}$/',
                Rule::unique('users', 'phone')->ignore($userId)
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId)
            ],
        ];
    }
}