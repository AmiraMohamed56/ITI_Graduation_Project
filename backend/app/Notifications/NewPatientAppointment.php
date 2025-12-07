<?php

// for doctors

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewPatientAppointment extends Notification implements ShouldQueue
{
    use Queueable;
    public $appointment;

    /**
     * Create a new notification instance.
     */
    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
         return (new MailMessage)
            ->subject('New Appointment Scheduled')
            ->greeting('Hello Dr. ' . $notifiable->name . '!')
            ->line('A new appointment has been scheduled with you.')
            ->line('**Patient:** ' . $this->appointment->patient->user->name)
            ->line('**Date:** ' . $this->appointment->schedule_date->format('F d, Y'))
            ->line('**Time:** ' . date('h:i A', strtotime($this->appointment->schedule_time)))
            ->action('View Appointment', url('/appointments/' . $this->appointment->id))
            ->line('Please review the appointment details.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'patient_name' => $this->appointment->patient->user->name,
            'schedule_date' => $this->appointment->schedule_date->format('Y-m-d'),
            'schedule_time' => $this->appointment->schedule_time,
            'message' => 'New appointment from ' . $this->appointment->patient->user->name,
            'type' => 'new_patient_appointment',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'appointment_id' => $this->appointment->id,
            'patient_name' => $this->appointment->patient->user->name,
            'schedule_date' => $this->appointment->schedule_date->format('Y-m-d'),
            'schedule_time' => $this->appointment->schedule_time,
            'message' => 'New appointment from ' . $this->appointment->patient->user->name,
            'type' => 'new_patient_appointment',
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
