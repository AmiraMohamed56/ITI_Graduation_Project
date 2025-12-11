<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class DoctorScheduleUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public $schedule;
    public $doctor;

    /**
     * Create a new notification instance.
     */
    public function __construct($schedule)
    {
        $this->schedule = $schedule;
        $this->doctor = $schedule->doctor;
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
            ->subject('Doctor Schedule Updated')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Dr. ' . $this->doctor->user->name . ' has updated their schedule.')
            ->line('**Updated Schedule Details:**')
            ->line('Day: ' . ucfirst($this->schedule->day_of_week))
            ->line('Time: ' . date('h:i A', strtotime($this->schedule->start_time)) . ' - ' . date('h:i A', strtotime($this->schedule->end_time)))
            ->line('Duration per appointment: ' . $this->schedule->appointment_duration . ' minutes')
            ->line('Status: ' . ($this->schedule->is_active ? 'Active' : 'Inactive'))
            ->action('View Schedule', url('/admin/schedules/' . $this->schedule->id))
            ->line('Please review the updated schedule details.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'schedule_id' => $this->schedule->id,
            'doctor_id' => $this->doctor->id,
            'doctor_name' => $this->doctor->user->name,
            'day_of_week' => $this->schedule->day_of_week,
            'start_time' => $this->schedule->start_time,
            'end_time' => $this->schedule->end_time,
            'message' => 'Dr. ' . $this->doctor->user->name . ' updated their schedule for ' . ucfirst($this->schedule->day_of_week),
            'type' => 'doctor_schedule_updated',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'schedule_id' => $this->schedule->id,
            'doctor_id' => $this->doctor->id,
            'doctor_name' => $this->doctor->user->name,
            'day_of_week' => $this->schedule->day_of_week,
            'start_time' => $this->schedule->start_time,
            'end_time' => $this->schedule->end_time,
            'message' => 'Dr. ' . $this->doctor->user->name . ' updated their schedule for ' . ucfirst($this->schedule->day_of_week),
            'type' => 'doctor_schedule_updated',
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
            'schedule_id' => $this->schedule->id,
            'doctor_id' => $this->doctor->id,
            'title' => 'Doctor Schedule Updated',
            'message' => "Dr. {$this->doctor->user->name} updated their schedule for " . ucfirst($this->schedule->day_of_week),
            'type' => 'schedule',
        ];
    }
}
