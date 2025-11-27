<?php

namespace Database\Factories;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Specialty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Doctor::class;

    public function definition(): array
    {
        return [
            // create a new user with role 'doctor' and use its id
            'user_id' => User::factory()->doctor(),
            'specialty_id' => Specialty::factory(),
            'bio' => $this->faker->paragraph(),
            'education' => $this->faker->sentence(6),
            'years_experience' => $this->faker->numberBetween(1, 30),
            'gender' => $this->faker->randomElement(['male','female']),
            'consultation_fee' => $this->faker->randomFloat(2, 20, 200),
            'rating' => $this->faker->randomFloat(2, 3, 5),
            'available_for_online' => $this->faker->boolean(70),
        ];
    }
}
