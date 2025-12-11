<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Contact;

class ContactReply extends Mailable
{
    use Queueable, SerializesModels;
    
    public $contact;
    public $replyMessage;

    public function __construct(Contact $contact, $replyMessage)
    {
        $this->contact = $contact;
        $this->replyMessage = $replyMessage;
    }

    public function build()
    {
        return $this->subject('Reply to Your Inquiry: ' . $this->contact->subject)
            ->view('emails.contact_reply')
            ->with([
                'replyMessage' => $this->replyMessage,
                'contact' => $this->contact,
            ]);
    }
}
