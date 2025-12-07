<?php

namespace App\Console\Commands;
use App\Models\Appointment;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Notifications\AppointmentReminder;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send appointment reminders (24h and 12h before)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for appointments needing reminders...');
        $tomorrow = now()->addDay();
        $dayAfterTomorrow = now()->addDays(2);

        // Get upcoming appointments
        $appointments = Appointment::whereBetween('schedule_date', [
                $tomorrow->startOfDay(),
                $dayAfterTomorrow->endOfDay()
            ])
            ->whereIn('status', ['pending', 'confirmed'])
            ->with(['patient.user', 'doctor.user'])
            ->get();

        $sent24h = 0;
        $sent12h = 0;

        foreach ($appointments as $appointment) {
            // Check 24-hour reminder
            if ($appointment->shouldSend24HourReminder()) {
                $appointment->patient->user->notify(new AppointmentReminder($appointment, 24));
                $appointment->mark24HourReminderSent();
                $sent24h++;
                $this->info("24h reminder sent for appointment #{$appointment->id}");
            }

            // Check 12-hour reminder
            if ($appointment->shouldSend12HourReminder()) {
                $appointment->patient->user->notify(new AppointmentReminder($appointment, 12));
                $appointment->mark12HourReminderSent();
                $sent12h++;
                $this->info("12h reminder sent for appointment #{$appointment->id}");
            }
        }


        $this->info("Total 24h reminders sent: {$sent24h}");
        $this->info("Total 12h reminders sent: {$sent12h}");

        return Command::SUCCESS;
    }
}
