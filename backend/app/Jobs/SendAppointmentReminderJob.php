<?php

namespace App\Jobs;
use App\Models\Appointment;
use App\Notifications\AppointmentReminder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendAppointmentReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $appointment;
    public $hoursUntil;
    /**
     * Create a new job instance.
     */
    public function __construct(Appointment $appointment, $hoursUntil)
    {
        $this->appointment = $appointment;
        $this->hoursUntil = $hoursUntil;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Check if appointment still exists and is not cancelled
        $appointment = Appointment::find($this->appointment->id);

        if (!$appointment || $appointment->status === 'cancelled') {
            Log::info("Skipping reminder for appointment #{$this->appointment->id} - cancelled or deleted");
            return; // Don't send reminder if cancelled
        }

        // Load relationships
        $appointment->load(['patient.user', 'doctor.user']);

        // Send the reminder notification
        $appointment->patient->user->notify(
            new AppointmentReminder($appointment, $this->hoursUntil)
        );

        // Mark as sent
        if ($this->hoursUntil == 24) {
            $appointment->update(['reminder_24h_sent_at' => now()]);
        } elseif ($this->hoursUntil == 12) {
            $appointment->update(['reminder_12h_sent_at' => now()]);
        }
        Log::info("Reminder sent for appointment #{$appointment->id} - {$this->hoursUntil}h before");
    }
}
