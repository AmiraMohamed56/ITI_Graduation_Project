<?php
namespace App\Http\Resources\Booking;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingAppointmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => (string)$this->id,
            'patient_id' => $this->patient_id,
            'doctor_id' => (string)$this->doctor_id,
            'doctor_schedule_id' => $this->doctor_schedule_id,
            'schedule_date' => $this->schedule_date,
            'schedule_time' => $this->schedule_time,
            'type' => $this->type,
            'notes' => $this->notes,
            'status' => $this->status,
        ];
    }
}
