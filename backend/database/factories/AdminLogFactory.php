<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\AdminLog;

class AdminLogFactory extends Factory
{
    protected $model = AdminLog::class;

    public function definition()
    {
        $users = User::pluck('id')->toArray(); 

        $actions = ['login', 'logout', 'create', 'update', 'delete', 'send_notification'];
        $models = ['User', 'Patient', 'Doctor', 'Appointment', 'Notification'];

        return [
            'user_id' => $this->faker->randomElement($users),
            'action' => $this->faker->randomElement($actions),
            'model' => $this->faker->randomElement($models),
            'model_id' => $this->faker->numberBetween(1, 100),
            'description' => $this->faker->sentence(),
            'ip_address' => $this->faker->ipv4(),
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'updated_at' => now(),
        ];
    }
}
