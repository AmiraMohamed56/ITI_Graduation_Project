<?php

namespace Database\Factories;
use App\Models\Specialty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Specialty>
 */
class SpecialtyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Specialty::class;

    public function definition(): array
    {
        $list = ['Cardiology','Dermatology','Neurology','Pediatrics','Orthopedics','ENT','Psychiatry','General'];
        return [
            'name' => $this->faker->randomElement($list),
        ];
    }
}
