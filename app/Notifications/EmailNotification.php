<?php

namespace App\Notifications;

use App\Mail\AdminOrderActionMail;
use App\Mail\ContactMail;
use App\Mail\MailType;
use App\Mail\NewOrderRecievedMail;
use App\Mail\OrderCancelationMail;
use App\Mail\OrderConfirmationMail;
use App\Mail\UserNotificationMail;
use Illuminate\Support\Facades\Config;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmailNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $mail_attributes;

    public function __construct($mail_attributes,private int $type)
    {
        $this->mail_attributes = $mail_attributes;
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
        $mail_attribute=$this->mail_attributes;
        $mail_to_email = $this->mail_attributes['mail_to_email'];
        $mail_to_name = $this->mail_attributes['mail_to_name'];
        $mailable=null;
        switch($this->type){
            case MailType::NEWORDERRECIEVED:
                $mailable= new \App\Mail\NewOrderRecievedMail($mail_to_email,$this->mail_attributes['mail_body']['order'],$this->mail_attributes['mail_body']['is_for_admin'],$this->mail_attributes['mail_attachement']);
                break;
            case MailType::ADMINORDERACTION:
                $mailable= new \App\Mail\AdminOrderActionMail($mail_to_email,$this->mail_attributes['mail_body']['order'],$this->mail_attributes['mail_body']['action']);
                break;
            case MailType::ORDERCONFIRMATION:
                $mailable= new \App\Mail\OrderConfirmationMail($mail_to_email,$this->mail_attributes['mail_body']['order'],$this->mail_attributes['mail_body']['is_for_admin'],$this->mail_attributes['mail_body']['has_exclam'],$this->mail_attributes['mail_attachement']);
                break;
            case MailType::ORDERCANCELATION:
                $mailable= new \App\Mail\OrderCancelationMail($this->mail_attributes['mail_body']['order'],$mail_to_email,$this->mail_attributes['mail_body']['order_id'],$this->mail_attributes['mail_body']['action']);
                break;
            case MailType::USERNOTIFICATION:
                $mailable= new \App\Mail\UserNotificationMail($mail_to_email);
                break;
            case MailType::ACCOUNTAPPROVEREQUEST:
                $mailable= new \App\Mail\AccountApproveRequestMail($mail_to_email);
                break;
            case MailType::USERACCOUNTCREATED:
                $mailable= new \App\Mail\UserAccountCreatedMail($mail_to_email);
                break;
            case MailType::CONTACT:
                $mailable= new ContactMail($mail_to_email,$this->mail_attributes['mail_body']['contact']);
                break;
        }
        return $mailable;
        // $mail_attributes = $this->mail_attributes;
        // $mail_from_email = config('app.mail_from_email');
        // $mail_from_name = config('app.mail_from_name');
        // if (!isset($mail_attributes['mail_body']) || empty($mail_attributes['mail_body'])) {
        //     $mail_attributes['mail_body'] = [];
        // }
        // if (isset($mail_attributes['mail_template']) && !empty($mail_attributes['mail_template'])) {

        //     if (isset($mail_attributes['mail_attachement']) && !empty($mail_attributes['mail_attachement'])) {

        //         return (new MailMessage)
        //             ->from($mail_from_email, $mail_from_name)
        //             ->subject($mail_attributes['mail_subject'])
        //             ->markdown($mail_attributes['mail_template'], $mail_attributes['mail_body'])
        //             ->attach($mail_attributes['mail_attachement']['file_full_path'], ['as' => $mail_attributes['mail_attachement']['file_name'], 'mime' => $mail_attributes['mail_attachement']['file_mime']]);
        //     } else {
        //         return (new MailMessage)
        //             ->from($mail_from_email, $mail_from_name)
        //             ->subject($mail_attributes['mail_subject'])
        //             ->markdown($mail_attributes['mail_template'], $mail_attributes['mail_body']);
        //     }
        // } else {
        //     return (new MailMessage)
        //         ->subject($mail_attributes['mail_subject'])
        //         ->line($mail_attributes['mail_body']['description'])
        //         ->action($mail_attributes['mail_body']['action_title'], url($mail_attributes['mail_body']['action_url']));
        // }
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
