<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;


class AppointmentCancelled extends Notification implements ShouldQueue
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
            ->subject('Appointment Cancelled')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your appointment has been cancelled.')
            ->line('**Cancelled Appointment:**')
            ->line('Doctor: Dr. ' . $this->appointment->doctor->user->name)
            ->line('Date: ' . $this->appointment->schedule_date->format('F d, Y'))
            ->line('Time: ' . date('h:i A', strtotime($this->appointment->schedule_time)))
            ->line('If you would like to book a new appointment, please contact us.')
            ->line('We apologize for any inconvenience.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'doctor_name' => $this->appointment->doctor->user->name,
            'schedule_date' => $this->appointment->schedule_date->format('Y-m-d'),
            'schedule_time' => $this->appointment->schedule_time,
            'message' => 'Your appointment with Dr. ' . $this->appointment->doctor->user->name . ' has been cancelled',
            'type' => 'appointment_cancelled',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'appointment_id' => $this->appointment->id,
            'doctor_name' => $this->appointment->doctor->user->name,
            'schedule_date' => $this->appointment->schedule_date->format('Y-m-d'),
            'schedule_time' => $this->appointment->schedule_time,
            'message' => 'Your appointment with Dr. ' . $this->appointment->doctor->user->name . ' has been cancelled',
            'type' => 'appointment_cancelled',
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
            'title' => 'Appointment Cancelled',
            'message' => "Your appointment with Dr. {$this->appointment->doctor->user->name} has been cancelled",
            'type' => 'appointment',
        ];
    }
}
