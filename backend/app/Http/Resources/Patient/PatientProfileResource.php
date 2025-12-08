<?php

namespace App\Http\Resources\Patient;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        $patient = $this;

        // Upcoming appointments based on schedule_date
        $upcoming = $patient->appointments
            ->filter(fn($a) => $a->schedule_date >= now()->toDateString())
            ->sortBy('schedule_date')
            ->values()
            ->map(function ($app) {
                return [
                    'id' => $app->id,
                    'doctor_name' => $app->doctor->user->name ?? null,
                    'specialty' => $app->doctor->specialty ?? null,
                    'date' => $app->schedule_date,
                    'time' => $app->schedule_time,
                    'status' => $app->status,
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

            'age' => null, // still no birth_date in schema

            // medical history
            'medical_history' => $patient->medicalRecords->map(function ($rec) {
                return [
                    'id' => $rec->id,
                    'symptoms' => $rec->symptoms,
                    'diagnosis' => $rec->diagnosis,
                    'medication' => $rec->medication,
                    'created_at' => $rec->created_at ? $rec->created_at->toDateString() : null,
                ];
            })->values(),

            // FULL appointments details
            'appointments' => $patient->appointments->map(function ($app) {
                return [
                    'id' => $app->id,
                    'type' => $app->type,
                    'status' => $app->status,
                    'date' => $app->schedule_date,
                    'time' => $app->schedule_time,
                    'price' => $app->price,
                    'notes' => $app->notes,

                    // doctor full details
                    'doctor' => [
                        'id' => $app->doctor->id ?? null,
                        'name' => $app->doctor->user->name ?? null,
                        'email' => $app->doctor->user->email ?? null,
                        'phone' => $app->doctor->user->phone ?? null,
                        'specialty' => $app->doctor->specialty ?? null,
                        'experience_years' => $app->doctor->experience_years ?? null,
                        'bio' => $app->doctor->bio ?? null,
                        'profile_pic' => $app->doctor->user->profile_pic
                            ? asset('storage/' . $app->doctor->user->profile_pic)
                            : null,
                    ],

                    // schedule details
                    'schedule' => [
                        'id' => $app->doctorSchedule->id ?? null,
                        'day' => $app->doctorSchedule->day ?? null,
                        'start_time' => $app->doctorSchedule->start_time ?? null,
                        'end_time' => $app->doctorSchedule->end_time ?? null,
                    ],

                    // payment details
                    'payment' => $app->payment ? [
                        'id' => $app->payment->id,
                        'amount' => $app->payment->amount,
                        'method' => $app->payment->method,
                        'status' => $app->payment->status,
                    ] : null,

                    // invoice
                    'invoice' => $app->invoice ? [
                        'id' => $app->invoice->id,
                        'total' => $app->invoice->total,
                        'status' => $app->invoice->status,
                    ] : null,

                    // medical record for THIS appointment
                    'record' => $app->medicalRecord ? [
                        'id' => $app->medicalRecord->id,
                        'symptoms' => $app->medicalRecord->symptoms,
                        'diagnosis' => $app->medicalRecord->diagnosis,
                        'medication' => $app->medicalRecord->medication,
                    ] : null,
                ];
            }),

            'upcoming_appointments' => $upcoming,
            'appointments_count' => $patient->appointments()->count(),
            'medical_records_count' => $patient->medicalRecords()->count(),
            'created_at' => $patient->created_at ? $patient->created_at->toDateTimeString() : null,
        ];
    }
}
