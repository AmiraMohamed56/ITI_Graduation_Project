<?php

namespace App\Http\Requests\medical_records;

use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreMedicalRecordRequest extends FormRequest
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


    protected function prepareForValidation()
    {
        // Get appointment and auto-fill doctor_id and patient_id
        if ($this->appointment_id) {
            $appointment = Appointment::find($this->appointment_id);

            if ($appointment) {
                $this->merge([
                    'doctor_id' => Auth::user()->doctor->id,
                    'patient_id' => $appointment->patient_id,
                ]);
            }
        }
    }
    public function rules(): array
    {
        return [
            'appointment_id' => [
                'required',
                'exists:appointments,id',
                // Ensure appointment belongs to the authenticated doctor
                function ($attribute, $value, $fail) {
                    $appointment = Appointment::find($value);
                    if ($appointment && $appointment->doctor_id !== Auth::user()->doctor->id) {
                        $fail('This appointment does not belong to you.');
                    }
                },
                // Ensure appointment doesn't already have a medical record
                function ($attribute, $value, $fail) {
                    $exists = MedicalRecord::where('appointment_id', $value)->exists();
                    if ($exists) {
                        $fail('This appointment already has a medical record.');
                    }
                },
            ],
            'doctor_id' => 'required|exists:doctors,id',
            'patient_id' => 'required|exists:patients,id',
            'symptoms' => 'nullable|string|max:2000',
            'diagnosis' => 'nullable|string|max:2000',
            'medication' => 'nullable|string|max:2000',
            'medical_files' => 'nullable|array',
            'medical_files.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
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
            'appointment_id.required' => 'Appointment is required',
            'appointment_id.exists' => 'Selected appointment does not exist',
            'doctor_id.required' => 'Doctor information is required',
            'patient_id.required' => 'Patient information is required',
            'symptoms.max' => 'Symptoms cannot exceed 2000 characters',
            'diagnosis.max' => 'Diagnosis cannot exceed 2000 characters',
            'medication.max' => 'Medication cannot exceed 2000 characters',
            'medical_files.*.file' => 'Each medical file must be a valid file',
            'medical_files.*.mimes' => 'Medical files must be PDF, JPG, PNG, DOC, or DOCX',
            'medical_files.*.max' => 'Each medical file cannot exceed 10MB',
        ];
    }

     public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        // Ensure doctor_id and patient_id are included
        if (!isset($validated['doctor_id'])) {
            $validated['doctor_id'] = $this->doctor_id;
        }
        if (!isset($validated['patient_id'])) {
            $validated['patient_id'] = $this->patient_id;
        }

        return is_null($key) ? $validated : ($validated[$key] ?? $default);
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    // public function attributes(): array
    // {
    //     return [
    //         'appointment_id' => 'appointment',
    //         'medical_files.*' => 'medical file',
    //     ];
    // }
}
