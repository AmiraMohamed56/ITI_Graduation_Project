<?php

namespace App\Http\Resources\medical_specialities;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicalSpecialitiesResource extends JsonResource
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
            'name' => $this->name,
            'doctors_count' => $this->when(isset($this->doctors_count), function () {
                return $this->doctors_count;
            }, function () {
                return $this->doctors()->count();
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
