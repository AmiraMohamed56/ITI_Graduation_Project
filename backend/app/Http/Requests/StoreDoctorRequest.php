<?php

namespace App\Http\Requests;

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
            'password' => 'required|string|min:8|confirmed',

            // From Doctor model
            'specialty_id' => 'required|exists:specialties,id',
            'bio' => 'nullable|string',
            'education' => 'nullable|string',
            'years_experience' => 'nullable|integer|min:0',
            'gender' => 'nullable|in:male,female',
            'consultation_fee' => 'nullable|numeric|min:0',
            'available_for_online' => 'nullable|boolean',
        ];
    }
}
