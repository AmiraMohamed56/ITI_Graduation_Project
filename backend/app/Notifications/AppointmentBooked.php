<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class AppointmentBooked extends Notification implements ShouldQueue
{
    use Queueable;
    public $appointment;

    //  Create a new notification instance.
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
            ->subject('Appointment Confirmation')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your appointment has been successfully booked.')
            ->line('**Appointment Details:**')
            ->line('Doctor: Dr. ' . $this->appointment->doctor->user->name)
            ->line('Date: ' . $this->appointment->schedule_date->format('F d, Y'))
            ->line('Time: ' . date('h:i A', strtotime($this->appointment->schedule_time)))
            ->line("Status: " . ucfirst($this->appointment->status))
            ->action('View Appointment', url('/appointments/' . $this->appointment->id))
            ->line('You will receive reminders 24 and 12 hours before your appointment.')
            ->line('Thank you for using our clinic!');
    }


    public function toDatabase($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'doctor_name' => $this->appointment->doctor->user->name,
            'patient_name' => $this->appointment->patient->user->name,
            'schedule_date' => $this->appointment->schedule_date->format('Y-m-d'),
            'schedule_time' => $this->appointment->schedule_time,
            'message' => 'New appointment booked with Dr. ' . $this->appointment->doctor->user->name,
            'type' => 'appointment_booked',
        ];
    }


    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'appointment_id' => $this->appointment->id,
            'doctor_name' => $this->appointment->doctor->user->name,
            'patient_name' => $this->appointment->patient->user->name,
            'schedule_date' => $this->appointment->schedule_date->format('Y-m-d'),
            'schedule_time' => $this->appointment->schedule_time,
            'message' => 'New appointment booked with Dr. ' . $this->appointment->doctor->user->name,
            'type' => 'appointment_booked',
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
            'title' => 'Appointment Booked Successfully',
            'message' => "Your appointment with Dr. {$this->appointment->doctor->user->name} has been confirmed for " .
                        \Carbon\Carbon::parse($this->appointment->schedule_date)->format('M d, Y'),
            'type' => 'appointment',
        ];
    }
}
