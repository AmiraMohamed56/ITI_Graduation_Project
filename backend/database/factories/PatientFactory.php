<?php

namespace Database\Factories;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Patient::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->patient(),
            'blood_type' => $this->faker->randomElement(['A+','A-','B+','B-','O+','O-','AB+','AB-']),
            'chronic_diseases' => $this->faker->boolean(40) ? $this->faker->sentence() : null,
        ];
    }
}
