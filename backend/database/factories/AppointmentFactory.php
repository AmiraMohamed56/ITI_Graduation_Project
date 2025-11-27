<?php

namespace Database\Factories;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Appointment::class;
    public function definition(): array
    {
        $doctor = Doctor::factory()->create();
        // ensure schedule for that doctor exists
        $schedule = DoctorSchedule::factory()->create(['doctor_id' => $doctor->id]);

        return [
            'patient_id' => Patient::factory(),
            'doctor_id' => $doctor->id,
            'doctor_schedule_id' => $schedule->id,
            'schedule_date' => $this->faker->dateTimeBetween('+1 days','+30 days')->format('Y-m-d'),
            'schedule_time' => $schedule->start_time,
            'type' => $this->faker->randomElement(['consultation','follow_up','telemedicine']),
            'status' => $this->faker->randomElement(['pending','confirmed','cancelled','completed']),
            'price' => $this->faker->randomFloat(2, 20, 200),
            'notes' => $this->faker->sentence(),
        ];
    }
}
