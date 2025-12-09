<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Specialty;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\DoctorSchedule;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\MedicalFile;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Invoice;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('en_US');

        // 1. CREATE ADMIN
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@clinic.test',
            'phone' => '+12025550111',
            'role' => 'admin',
            'password' => bcrypt('admin123'),
        ]);

        // 2. CREATE SPECIALTIES
        $specialties = ['Cardiology','Dermatology','Neurology','Pediatrics','Orthopedic','Ophthalmology'];
        foreach ($specialties as $spec) {
            Specialty::factory()->create(['name' => $spec]);
        }

        // 3. CREATE DOCTORS AND THEIR USERS
        $doctors = Doctor::factory(8)->create()->each(function ($doctor) use ($faker) {
            DoctorSchedule::factory(rand(1,3))->create(['doctor_id' => $doctor->id]);
        });

        // 4. CREATE PATIENT USERS
        $patients = Patient::factory(30)->create();

        // 5. CREATE APPOINTMENTS
        $appointments = Appointment::factory(50)->create()->each(function($appointment) {
            // optionally, you could create linked MedicalRecords here
            MedicalRecord::factory(rand(0,1))->create([
                'appointment_id' => $appointment->id,
                'doctor_id' => $appointment->doctor_id,
                'patient_id' => $appointment->patient_id,
            ]);
        });

        // 6. CREATE MEDICAL FILES for MedicalRecords
        MedicalRecord::all()->each(function($record) {
            MedicalFile::factory(rand(0,2))->create([
                'medical_record_id' => $record->id,
            ]);
        });

        // 7. CREATE INVOICES linked to appointments
        $invoices = Invoice::factory(40)->create()->each(function($invoice) use ($appointments) {
            $appointment = $appointments->random();
            $invoice->update([
                'appointment_id' => $appointment->id,
                'total' => $appointment->price ?? rand(50,200),
            ]);
        });

        // 8. CREATE PAYMENTS linked to invoices
        $invoices->take(25)->each(function($invoice) use ($faker) {
            Payment::factory()->create([
                'appointment_id' => $invoice->appointment_id,
                'patient_id' => $invoice->appointment->patient_id,
                'amount' => $invoice->total,
                'status' => 'paid',
                'method' => $faker->randomElement(['card','cash','wallet']),
            ]);
        });

        // 9. CREATE REVIEWS (patients review doctors)
        Review::factory(50)->create();

        echo "\n✔ Database seeded successfully with realistic English data.\n";
        $this->call([
            AdminLogSeeder::class,
        ]);
    }
}
