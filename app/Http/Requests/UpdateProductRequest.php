<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function rules(): array
    {
        $productId = $this->route('product')->id;
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:0',
            'availability' => 'required|in:В наличии,Нет в наличии',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ];
    }
}