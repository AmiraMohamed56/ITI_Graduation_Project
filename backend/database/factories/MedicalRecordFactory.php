<?php

namespace Database\Factories;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicalRecord>
 */
class MedicalRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = MedicalRecord::class;

    public function definition(): array
    {
        $appointment = Appointment::factory()->create();

        return [
            'appointment_id' => $appointment->id,
            'doctor_id' => $appointment->doctor_id,
            'patient_id' => $appointment->patient_id,
            'symptoms' => $this->faker->sentence(),
            'diagnosis' => $this->faker->sentence(),
            'medication' => $this->faker->sentence(),
        ];
    }
}
