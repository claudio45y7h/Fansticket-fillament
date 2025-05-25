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

    public $user;
    public $webviewUrl;

    public function __construct($user)
    {
        $this->user = $user;
        $this->webviewUrl = route('mail.welcome', ['user' => $user->id]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Bienvenido a Fan Tickets!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
            with: [
                'user' => $this->user,
                'webviewUrl' => $this->webviewUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
