<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewContactMessage extends Notification implements ShouldQueue
{
    use Queueable;
    public $contact;

    /**
     * Create a new notification instance.
     */
    public function __construct($contact)
    {
        $this->contact = $contact;
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
            ->subject('New Contact Message Received')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have received a new contact message.')
            ->line('**Contact Details:**')
            ->line('Name: ' . $this->contact->name)
            ->line('Email: ' . $this->contact->email)
            ->line('Phone: ' . ($this->contact->phone ?? 'Not provided'))
            ->line('Subject: ' . $this->contact->subject)
            ->line('Message: ' . $this->contact->message)
            ->action('View Contact Message', url('/admin/contacts'))
            ->line('Please respond to this message as soon as possible.');
    }


    public function toDatabase($notifiable)
    {
        return [
            'contact_id' => $this->contact->id,
            'contact_name' => $this->contact->name,
            'contact_email' => $this->contact->email,
            'subject' => $this->contact->subject,
            'message' => 'New contact message from ' . $this->contact->name,
            'type' => 'new_contact_message',
        ];
    }


     /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'contact_id' => $this->contact->id,
            'contact_name' => $this->contact->name,
            'contact_email' => $this->contact->email,
            'subject' => $this->contact->subject,
            'message' => 'New contact message from ' . $this->contact->name,
            'type' => 'new_contact_message',
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
            'contact_id' => $this->contact->id,
            'title' => 'New Contact Message',
            'message' => "{$this->contact->name} sent a message: {$this->contact->subject}",
            'type' => 'contact',
        ];
    }
}
