<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminLog;

class AdminLogSeeder extends Seeder
{
    public function run()
    {
        
        AdminLog::factory()->count(10)->create();
    }
}
