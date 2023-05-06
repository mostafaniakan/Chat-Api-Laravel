<?php

namespace App\Notifications;

use App\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Sms extends Notification
{
    private $users;
    use Queueable;

    private $getCode;


        /**
         * Create a new notification instance.
         *
         * @return void
         */
    public function __construct($user,$code)
    {

        $this->users = $user;
        $this->getCode=$code;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
//        return ['mail', SmsChannel::class];
        return [ SmsChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from('mostafa@info.com')
            ->line($this->getCode)
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

//    create sms message
    public function toSms($notifiable)
    {
        return [
            $this->getCode
        ];
    }
}
