<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminOrderActionMail extends Mailable
{
    use Queueable, SerializesModels;
    public $from;
    /**
     * Create a new message instance.
     */
    public function __construct(public $email,private $order, private $action,private $label)
    {
        
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: config("mail.mail_team_name")." : Your Order is " . $this->label,
            from: new Address(config("mail.from.address"),config("mail.from.name")),
            to:$this->email
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view:"emails.admin_order_action",
            with:[
                $this->order,
                $this->action
            ],
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
