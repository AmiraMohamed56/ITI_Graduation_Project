<?php

namespace Database\Factories;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DoctorSchedule>
 */
class DoctorScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = DoctorSchedule::class;

    public function definition(): array
    {
        $days = ['sunday','monday','tuesday','wednesday','thursday','friday','saturday'];
        $start = $this->faker->time('H:i:s');
        $end = date('H:i:s', strtotime($start) + 3600*4); // +4 hours
        return [
            'doctor_id' => Doctor::factory(),
            'day_of_week' => $this->faker->randomElement($days),
            'start_time' => $start,
            'end_time' => $end,
            'appointment_duration' => 20,
            'is_active' => true,
        ];
    }
}
