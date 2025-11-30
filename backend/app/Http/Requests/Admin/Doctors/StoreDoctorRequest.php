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


    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.min' => 'The name must be at least 3 characters.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'The email has already been taken.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'specialty_id.required' => 'The specialty field is required.',
            'specialty_id.exists' => 'The selected specialty is invalid.',
            'years_experience.required' => 'The years of experience field is required.',
            'gender.required' => 'The gender field is required.',
            'consultation_fee.required' => 'The consultation fee field is required.',
        ];
    }
}
