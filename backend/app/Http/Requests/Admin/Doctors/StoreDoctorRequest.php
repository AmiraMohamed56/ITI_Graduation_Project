<?php

namespace App\Http\Requests\Admin\Doctors;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return false;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // From User model
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',

            // From Doctor model
            'specialty_id' => 'required|integer|exists:specialties,id',
            'bio' => 'nullable|string',
            'education' => 'required|string',
            'years_experience' => 'required|integer|min:0',
            'gender' => 'required|in:male,female',
            'consultation_fee' => 'required|numeric|min:0',
            'available_for_online' => 'nullable|boolean',
        ];
    }
}
