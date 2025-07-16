<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    // public string $User;
    public $StoreUser;
    /**
     * Create a new message instance.
     */
    public function __construct($StoreUser)
    {
        //
        // $this->$User = $StoreUser;
        // $this->$StoreUser = $User;
        $this->$StoreUser = $StoreUser;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            // subject: 'Welcome Mail',
            subject: 'Welcome To Our Application In Mail', /// this is for show in  the subjects or the titles in emails 
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            // view: 'view.name',
            view: 'Mails.WelcomeUser',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
