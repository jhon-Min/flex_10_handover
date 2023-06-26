<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(private $mail,private $order,private $is_for_admin,private $hasExclam,private array $attachment)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: config("mail.mail_team_name").' : Your Order'. (($this->hasExclam)?"!":""),
            to: [$this->mail],
            from: new Address(config("mail.from.address"),config("mail.from.name"))
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.order_confirmation',
            with:[
                'order' => $this->order,
                'is_for_admin' => 1,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath(storage_path(str_replace(config("app.url")."/storage","app/public",$this->attachment["file_full_path"])))->as($this->attachment['file_name'])->withMime($this->attachment['file_mime']),
        ];
    }
}
