<?php
namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize()
    {
        return true; // adapt to auth later
    }

    public function rules()
    {
        return [
            'patient_id' => 'required|integer|exists:users,id',
            'doctor_id' => 'required|integer|exists:doctors,id',
            'doctor_schedule_id' => 'nullable|integer|exists:doctor_schedules,id',
            'schedule_date' => 'required|string', // you're using day names; if you change to date use date rule
            'schedule_time' => 'required|date_format:H:i',
            'type' => 'required|in:consultation,follow_up,telemedicine',
            'notes' => 'nullable|string',
        ];
    }
}
