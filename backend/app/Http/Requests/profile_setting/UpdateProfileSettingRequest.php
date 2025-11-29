<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {
        return [
            // User fields
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $this->route('doctor'),
            'phone' => 'sometimes|string|max:20',
            'password' => 'sometimes|nullable|string|min:8|confirmed',
            'password_confirmation' => 'sometimes|nullable|required_with:password',
            'profile_pic' => 'sometimes|nullable|image|mimes:jpg,jpeg,png|max:2048',

            // Doctor fields
            'specialty_id' => 'sometimes|exists:specialties,id',
            'bio' => 'nullable|string|max:1000',
            'education' => 'sometimes|string|max:255',
            'years_experience' => 'sometimes|integer|min:0|max:70',
            'gender' => 'sometimes|in:male,female',
            'consultation_fee' => 'nullable|numeric|min:0|max:99999999.99',
            'available_for_online' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            // User messages
            'name.string' => 'Name must be a valid string',
            'email.email' => 'Please provide a valid email address',
            'email.unique' => 'This email is already taken',
            'phone.max' => 'Phone number cannot exceed 20 characters',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
            'password_confirmation.required_with' => 'Password confirmation is required when changing password',
            'profile_pic.image' => 'Profile picture must be an image',
            'profile_pic.mimes' => 'Profile picture must be JPG, JPEG, or PNG',
            'profile_pic.max' => 'Profile picture cannot exceed 2MB',

            // Doctor messages
            'specialty_id.exists' => 'Selected specialty does not exist',
            'years_experience.min' => 'Years of experience cannot be negative',
            'gender.in' => 'Gender must be either male or female',
            'consultation_fee.numeric' => 'Consultation fee must be a valid number',
        ];
    }
}
