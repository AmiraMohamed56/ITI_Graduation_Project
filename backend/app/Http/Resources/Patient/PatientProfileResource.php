<?php

namespace App\Http\Resources\Patient;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        $patient = $this;

        // upcoming appointments (if date field exists on appointments)
        $upcoming = $patient->appointments
            ->filter(fn($a) => isset($a->date) ? $a->date >= now()->toDateString() : true)
            ->sortBy('date')
            ->values()
            ->map(function ($app) {
                return [
                    'id' => $app->id,
                    'doctor_name' => $app->doctor->user->name ?? null,
                    'specialty' => $app->doctor->specialty ?? null,
                    'date' => $app->date ?? null,
                    'status' => $app->status ?? null,
                ];
            });

        return [
            'id' => $patient->id,
            'user_id' => $patient->user->id ?? null,
            'name' => $patient->user->name ?? null,
            'email' => $patient->user->email ?? null,
            'phone' => $patient->user->phone ?? null,
            'profile_pic' => $patient->user->profile_pic ? asset('storage/' . $patient->user->profile_pic) : null,
            'status' => $patient->user->status ?? null,
            'blood_type' => $patient->blood_type,
            'chronic_diseases' => $patient->chronic_diseases,
            'member_since' => $patient->user->created_at ? $patient->user->created_at->format('Y') : null,
            // age not present in schema (requires birth_date) -> return null
            'age' => null,
            // medical history from MedicalRecord model
            'medical_history' => $patient->medicalRecords->map(function ($rec) {
                return [
                    'id' => $rec->id,
                    'symptoms' => $rec->symptoms,
                    'diagnosis' => $rec->diagnosis,
                    'medication' => $rec->medication,
                    'created_at' => $rec->created_at ? $rec->created_at->toDateString() : null,
                ];
            })->values(),
            'upcoming_appointments' => $upcoming,
            'appointments_count' => $patient->appointments()->count(),
            'medical_records_count' => $patient->medicalRecords()->count(),
            'created_at' => $patient->created_at ? $patient->created_at->toDateTimeString() : null,
        ];
    }
}
