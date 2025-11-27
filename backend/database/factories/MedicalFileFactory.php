<?php

namespace Database\Factories;
use App\Models\MedicalFile;
use App\Models\MedicalRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicalFile>
 */
class MedicalFileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = MedicalFile::class;

    public function definition(): array
    {
        return [
            'medical_record_id' => MedicalRecord::factory(),
            'file_path' => 'uploads/medical_files/' . $this->faker->uuid() . '.pdf',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
