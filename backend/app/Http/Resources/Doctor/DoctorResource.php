<?php

namespace App\Http\Resources\Doctor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bio' => $this->bio,
            'education' => $this->education,
            'years_experience' => $this->years_experience,
            'gender' => $this->gender,
            'consultation_fee' => $this->consultation_fee,
            'rating' => $this->rating,
            'available_for_online' => (bool)$this->available_for_online,
            // 'created_at' => $this->created_at->format('Y-m-d'),

            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],

            'specialty' => new SpecialtyResource($this->specialty),

            'schedules' => ScheduleResource::collection($this->schedules),
        ];
    }
}
