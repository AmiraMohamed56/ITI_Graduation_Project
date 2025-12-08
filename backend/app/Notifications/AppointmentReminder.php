<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class AppointmentReminder extends Notification implements ShouldQueue
{
    use Queueable;
    public $appointment;
    public $hoursUntil;

    /**
     * Create a new notification instance.
     */
    public function __construct($appointment, $hoursUntil)
    {
        $this->appointment = $appointment;
        $this->hoursUntil = $hoursUntil;
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
            ->subject('Appointment Reminder - ' . $this->hoursUntil . ' Hours')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('This is a reminder about your upcoming appointment.')
            ->line('**Appointment Details:**')
            ->line('Doctor: Dr. ' . $this->appointment->doctor->user->name)
            ->line('Date: ' . $this->appointment->schedule_date->format('F d, Y'))
            ->line('Time: ' . date('h:i A', strtotime($this->appointment->schedule_time)))
            ->line('**Time Until Appointment:** ' . $this->hoursUntil . ' hours')
            ->action('View Appointment', url('/appointments/' . $this->appointment->id))
            ->line('Please arrive 10 minutes early.')
            ->line('If you need to reschedule, please contact us as soon as possible.');
    }


     public function toDatabase($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'doctor_name' => $this->appointment->doctor->user->name,
            'schedule_date' => $this->appointment->schedule_date->format('Y-m-d'),
            'schedule_time' => $this->appointment->schedule_time,
            'hours_until' => $this->hoursUntil,
            'message' => 'Reminder: Your appointment with Dr. ' . $this->appointment->doctor->user->name . ' is in ' . $this->hoursUntil . ' hours',
            'type' => 'appointment_reminder',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'appointment_id' => $this->appointment->id,
            'doctor_name' => $this->appointment->doctor->user->name,
            'schedule_date' => $this->appointment->schedule_date->format('Y-m-d'),
            'schedule_time' => $this->appointment->schedule_time,
            'hours_until' => $this->hoursUntil,
            'message' => 'Reminder: Your appointment with Dr. ' . $this->appointment->doctor->user->name . ' is in ' . $this->hoursUntil . ' hours',
            'type' => 'appointment_reminder',
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
            'appointment_id' => $this->appointment->id,
            'title' => "Appointment Reminder - {$this->hoursUntil}h",
            'message' => "Your appointment with Dr. {$this->appointment->doctor->user->name} is in {$this->hoursUntil} hours",
            'type' => 'reminder',
            'hours_until' => $this->hoursUntil,
        ];
    }
}
