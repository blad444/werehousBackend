<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:0',
            'availability' => 'required|in:В наличии,Нет в наличии',
            'photo' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ];
    }
}