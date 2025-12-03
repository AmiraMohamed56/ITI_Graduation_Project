<?php

namespace App\Http\Requests\schedule;

use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'day_of_week' => 'required|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'appointment_duration' => 'required|integer|min:5|max:480',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'day_of_week.required' => 'Day of week is required',
            'day_of_week.in' => 'Invalid day of week',
            'start_time.required' => 'Start time is required',
            'start_time.date_format' => 'Start time must be in HH:MM format',
            'end_time.required' => 'End time is required',
            'end_time.date_format' => 'End time must be in HH:MM format',
            'end_time.after' => 'End time must be after start time',
            'appointment_duration.required' => 'Appointment duration is required',
            'appointment_duration.min' => 'Appointment duration must be at least 5 minutes',
            'appointment_duration.max' => 'Appointment duration cannot exceed 8 hours',
        ];
    }
}
