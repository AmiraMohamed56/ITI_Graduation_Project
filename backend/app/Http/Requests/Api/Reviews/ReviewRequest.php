<?php

namespace App\Http\Requests\Api\Reviews;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ];
    }

     public function messages(): array
    {
        return [
            'patient_id.required' => 'Patient ID is required',
            'patient_id.exists' => 'Patient does not exist',
            'doctor_id.required' => 'Doctor ID is required',
            'doctor_id.exists' => 'Doctor does not exist',
            'rating.required' => 'Rating is required',
            'rating.integer' => 'Rating must be a number',
            'rating.min' => 'Rating must be at least 1',
            'rating.max' => 'Rating must not exceed 5',
            'comment.required' => 'Comment is required',
            'comment.max' => 'Comment must not exceed 1000 characters',
        ];
    }
}
