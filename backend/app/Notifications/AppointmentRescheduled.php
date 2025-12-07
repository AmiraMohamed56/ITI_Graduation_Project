<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;


class AppointmentRescheduled extends Notification implements ShouldQueue
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
            ->subject('Appointment Rescheduled')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your appointment has been rescheduled.')
            ->line('**New Appointment Details:**')
            ->line('Doctor: Dr. ' . $this->appointment->doctor->user->name)
            ->line('Date: ' . $this->appointment->schedule_date->format('F d, Y'))
            ->line('Time: ' . date('h:i A', strtotime($this->appointment->schedule_time)))
            ->action('View Appointment', url('/appointments/' . $this->appointment->id))
            ->line('If you did not request this change, please contact us immediately.')
            ->line('You will receive new reminders for the updated time.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'doctor_name' => $this->appointment->doctor->user->name,
            'schedule_date' => $this->appointment->schedule_date->format('Y-m-d'),
            'schedule_time' => $this->appointment->schedule_time,
            'message' => 'Your appointment with Dr. ' . $this->appointment->doctor->user->name . ' has been rescheduled',
            'type' => 'appointment_rescheduled',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'appointment_id' => $this->appointment->id,
            'doctor_name' => $this->appointment->doctor->user->name,
            'schedule_date' => $this->appointment->schedule_date->format('Y-m-d'),
            'schedule_time' => $this->appointment->schedule_time,
            'message' => 'Your appointment with Dr. ' . $this->appointment->doctor->user->name . ' has been rescheduled',
            'type' => 'appointment_rescheduled',
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
