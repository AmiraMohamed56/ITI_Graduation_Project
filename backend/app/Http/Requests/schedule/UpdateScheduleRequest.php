<?php

namespace App\Http\Requests\schedule;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
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
            'doctor_id' => 'sometimes|required|exists:doctors,id',
            'day_of_week' => 'sometimes|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i|after:start_time',
            'appointment_duration' => 'sometimes|integer|min:5|max:480',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'doctor_id.exists' => 'Selected doctor does not exist',
            'day_of_week.in' => 'Invalid day of week',
            'start_time.date_format' => 'Start time must be in HH:MM format',
            'end_time.date_format' => 'End time must be in HH:MM format',
            'end_time.after' => 'End time must be after start time',
            'appointment_duration.min' => 'Appointment duration must be at least 5 minutes',
            'appointment_duration.max' => 'Appointment duration cannot exceed 8 hours',
        ];
    }
}
