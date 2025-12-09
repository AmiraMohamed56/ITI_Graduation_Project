<?php

namespace App\Http\Resources\Reviews;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'rating' => $this->rating,
            'comment' => $this->comment,
            'created_at' => $this->created_at->format('Y-m-d'),

            'patient' => [
                'id' => $this->patient->id,
                'user' => [
                    'id' => $this->patient->user->id,
                    'name' => $this->patient->user->name,
                    'email' => $this->patient->user->email,
                    'profile_picture_url' => $this->patient->user->profile_pic
                        ? asset('storage/' . $this->patient->user->profile_pic)
                        : null,
            ],
        ],

            'doctor_id' => $this->doctor_id,
        ];
    }
}
