<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class patientupdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'blood_type' => 'sometimes|nullable|string|max:5',
            'chronic_diseases' => 'sometimes|nullable|string',
        ];
    }
}
