<?php

namespace App\Http\Requests\Admin\Doctors;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorRequest extends FormRequest
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
        $doctorId = $this->route('doctor')->id;
        $userId = $this->route('doctor')->user->id;

        return [
            // From User model
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email,'. $userId,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',

            // From Doctor model
            'specialty_id' => 'required|exists:specialties,id',
            'bio' => 'nullable|string',
            'education' => 'required|string',
            'years_experience' => 'nullable|integer|min:0',
            'gender' => 'nullable|in:male,female',
            'consultation_fee' => 'nullable|numeric|min:0',
            'available_for_online' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.min' => 'The name must be at least 3 characters.',
        ];
    }
}
