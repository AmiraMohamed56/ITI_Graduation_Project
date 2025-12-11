<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewPatientRegistered extends Notification implements ShouldQueue
{
    use Queueable;
    public $patient;


    /**
     * Create a new notification instance.
     */
    public function __construct($patient)
    {
        $this->patient = $patient;
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
            ->subject('New Patient Registration')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new patient has registered on the platform.')
            ->line('**Patient Details:**')
            ->line('Name: ' . $this->patient->user->name)
            ->line('Email: ' . $this->patient->user->email)
            ->line('Phone: ' . ($this->patient->user->phone ?? 'Not provided'))
            ->line('Registered: ' . $this->patient->created_at->format('F d, Y h:i A'))
            ->action('View Patient Profile', url('/admin/patients/' . $this->patient->id))
            ->line('Please review the new patient registration.');
    }


    public function toDatabase($notifiable)
    {
        return [
            'patient_id' => $this->patient->id,
            'patient_name' => $this->patient->user->name,
            'patient_email' => $this->patient->user->email,
            'message' => 'New patient ' . $this->patient->user->name . ' has registered',
            'type' => 'new_patient_registration',
        ];
    }


    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'patient_id' => $this->patient->id,
            'patient_name' => $this->patient->user->name,
            'patient_email' => $this->patient->user->email,
            'message' => 'New patient ' . $this->patient->user->name . ' has registered',
            'type' => 'new_patient_registration',
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
            'patient_id' => $this->patient->id,
            'title' => 'New Patient Registered',
            'message' => "{$this->patient->user->name} has registered as a new patient",
            'type' => 'patient',
        ];
    }
}
