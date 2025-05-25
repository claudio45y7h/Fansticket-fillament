<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $webviewUrl;

    public function __construct($order)
    {
        $this->order = $order;
        $this->webviewUrl = route('mail.order_confirmation', ['order' => $order->id]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Compra confirmada en Fan Tickets!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order_confirmation',
            with: [
                'order' => $this->order,
                'viewInBrowserUrl' => $this->webviewUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
