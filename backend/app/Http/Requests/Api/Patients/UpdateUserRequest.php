<?php

namespace App\Http\Requests\Api\Patients;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('id') ?? null; // expects route param {id} is patient id; we will use user's id via patient lookup
        // We can't directly unique:users,email,<userId> because route id is patient id,
        // Unique validation will be enforced in controller if needed.
        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                // unique validation will be handled in controller because route id is patient id
            ],
            'phone' => 'sometimes|nullable|string|max:30',
            'profile_pic' => 'sometimes|nullable|image|max:2048', // optional file upload
        ];
    }
}
