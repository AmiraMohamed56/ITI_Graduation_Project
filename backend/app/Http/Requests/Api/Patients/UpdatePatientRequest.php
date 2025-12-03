<?php

namespace App\Http\Requests\Patients;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFullPatientProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // USER fields
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255',
            'phone' => 'sometimes|nullable|string|max:30',
            'profile_pic' => 'sometimes|nullable|image|max:2048',

            // PATIENT fields
            'blood_type' => 'sometimes|nullable|string|max:5',
            'chronic_diseases' => 'sometimes|nullable|string',
        ];
    }
}
