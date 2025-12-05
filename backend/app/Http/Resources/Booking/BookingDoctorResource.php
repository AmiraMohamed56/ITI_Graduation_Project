<?php
namespace App\Http\Resources\Booking;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingDoctorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => (string)$this->id,
            'user_id' => $this->user_id,
            'specialty_id' => $this->specialty_id,
            'bio' => $this->bio,
            'education' => $this->education,
            'years_experience' => $this->years_experience,
            'gender' => $this->gender,
            'consultation_fee' => $this->consultation_fee,
            'rating' => $this->rating,
            'available_for_online' => (bool)$this->available_for_online,
            'user' => [
                'name' => $this->user?->name,
                'profile_pic' => $this->user?->profile_pic,
            ],
            'specialty' => [
                'name' => $this->specialty?->name,
            ],
        ];
    }
}
