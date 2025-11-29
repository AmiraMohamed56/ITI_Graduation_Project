<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicalRecordRequest extends FormRequest
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
            'appointment_id' => 'required|exists:appointments,id',
            'doctor_id' => 'required|exists:doctors,id',
            'patient_id' => 'required|exists:patients,id',
            'symptoms' => 'nullable|string|max:2000',
            'diagnosis' => 'nullable|string|max:2000',
            'medication' => 'nullable|string|max:2000',
            'medical_files' => 'nullable|array',
            'medical_files.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'appointment_id.required' => 'Appointment ID is required',
            'appointment_id.exists' => 'Selected appointment does not exist',
            'doctor_id.required' => 'Doctor ID is required',
            'doctor_id.exists' => 'Selected doctor does not exist',
            'patient_id.required' => 'Patient ID is required',
            'patient_id.exists' => 'Selected patient does not exist',
            'symptoms.max' => 'Symptoms cannot exceed 2000 characters',
            'diagnosis.max' => 'Diagnosis cannot exceed 2000 characters',
            'medication.max' => 'Medication cannot exceed 2000 characters',
            'medical_files.*.file' => 'Each medical file must be a valid file',
            'medical_files.*.mimes' => 'Medical files must be PDF, JPG, PNG, DOC, or DOCX',
            'medical_files.*.max' => 'Each medical file cannot exceed 10MB',
        ];
    }
}
