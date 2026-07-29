<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOperationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => 'required|in:Новый,В процессе,Выполнен,Отменен',
        ];
    }
}