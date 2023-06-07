<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Config;

class AccountApprovedSuccess extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = Config::get('constant.front_url');
        $help_line_number = Config::get('constant.HELP_LINE_NUMBER');
        return (new MailMessage)
            ->subject('Flexibledrive : Account Update')
            ->line('Welcome to the Flexible Drive Online Ordering Syetem.')
            ->line("Your account has been approved and you're ready to start ordering. Click the button below to get started, and if you have any issue feel free to give us a call on " . $help_line_number . ".")
            ->action('Login now', url($url));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
