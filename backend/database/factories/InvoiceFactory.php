<?php

namespace Database\Factories;
use App\Models\Appointment;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Invoice::class;
    public function definition(): array
    {
        $appointment = Appointment::factory()->create();
        return [
            'appointment_id' => $appointment->id,
            'total' => $appointment->price ?? $this->faker->randomFloat(2, 50, 300),
            'pdf_path' => 'invoices/' . $this->faker->uuid() . '.pdf',
        ];
    }
}
