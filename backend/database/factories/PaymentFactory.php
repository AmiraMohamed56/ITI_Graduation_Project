<?php

namespace Database\Factories;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Payment::class;

    public function definition(): array
    {
        $appointment = Appointment::factory()->create();

        return [
            'appointment_id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'amount' => $appointment->price ?? $this->faker->randomFloat(2, 50, 500),
            'status' => $this->faker->randomElement(['paid','refunded','failed']),
            'method' => $this->faker->randomElement(['card','wallet','cash']),
            'transaction_id' => strtoupper($this->faker->bothify('TXN-#####')),
        ];
    }
}
