<?php

namespace App\Http\Requests\medical_records;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicalRecordRequest extends FormRequest
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
            'symptoms' => 'nullable|string|max:2000',
            'diagnosis' => 'nullable|string|max:2000',
            'medication' => 'nullable|string|max:2000',
            'medical_files' => 'nullable|array',
            'medical_files.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'remove_files' => 'nullable|array',
            'remove_files.*' => 'exists:medical_files,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'symptoms.max' => 'Symptoms cannot exceed 2000 characters',
            'diagnosis.max' => 'Diagnosis cannot exceed 2000 characters',
            'medication.max' => 'Medication cannot exceed 2000 characters',
            'medical_files.*.file' => 'Each medical file must be a valid file',
            'medical_files.*.mimes' => 'Medical files must be PDF, JPG, PNG, DOC, or DOCX',
            'medical_files.*.max' => 'Each medical file cannot exceed 10MB',
            'remove_files.*.exists' => 'Selected file does not exist',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'medical_files.*' => 'medical file',
            'remove_files.*' => 'file to remove',
        ];
    }
}
