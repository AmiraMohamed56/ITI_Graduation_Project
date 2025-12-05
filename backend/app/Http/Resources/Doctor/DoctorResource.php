<?php

namespace App\Http\Resources\Doctor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Doctor\SpecialtyResource;
use App\Http\Resources\Doctor\ScheduleResource;
use App\Http\Resources\Reviews\ReviewResource;


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
            'user_id' => $this->user_id,
            'specialty_id' => $this->specialty_id,
            'bio' => $this->bio,
            'education' => $this->education,
            'years_experience' => $this->years_experience,
            'gender' => $this->gender,
            'consultation_fee' => $this->consultation_fee,
            'rating' => $this->rating,
            'available_for_online' => $this->available_for_online,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // User information
            'user' => [
                'id' => $this->user->id ?? null,
                'name' => $this->user->name ?? null,
                'email' => $this->user->email ?? null,
                'phone' => $this->user->phone ?? null,
                'profile_pic' => $this->user->profile_pic ?? null,
                'profile_picture_url' => $this->user->profile_picture_url ?? null,
                'status' => $this->user->status ?? null,
            ],

            // Specialty information
            'specialty' => $this->whenLoaded('specialty', function () {
                return [
                    'id' => $this->specialty->id,
                    'name' => $this->specialty->name,
                ];
            }),

            // 'specialty' => new SpecialtyResource($this->specialty),

            // schedules information
            'schedules' => ScheduleResource::collection($this->schedules),

            // rating & reviews information
            'rating_avg'    => $this->whenLoaded('reviews', fn() => round($this->reviews->avg('rating'), 1), 0),
            'reviews_count' => $this->whenLoaded('reviews', fn() => $this->reviews->count(), 0),
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
        ];
    }
}
