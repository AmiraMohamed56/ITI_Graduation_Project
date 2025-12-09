<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// Schedule appointment reminders to run every hour
Schedule::command('appointments:send-reminders')
    ->hourly()
    ->withoutOverlapping()
    ->runInBackground();


//run at specific times
Schedule::command('appointments:send-reminders')
    ->dailyAt('08:00')
    ->dailyAt('12:00')
    ->dailyAt('16:00')  // 4 PM
    ->dailyAt('20:00')  // 8 PM
    ->withoutOverlapping();

// Optional: Clean old notifications weekly
// Schedule::command('model:prune', [
//     '--model' => 'Illuminate\Notifications\DatabaseNotification',
// ])
//     ->weekly()
//     ->withoutOverlapping();
