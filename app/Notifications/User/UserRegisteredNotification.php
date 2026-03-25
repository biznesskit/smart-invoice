<?php

namespace App\Notifications\User;

use App\Channels\SmsChannel;
use App\Channels\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRegisteredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( )
    {

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', SmsChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Account created')
            ->line('Dear ' .  ucfirst($notifiable->first_name) . ucfirst($notifiable->last_name))
            ->line('Your account has been successfully created')
            ->line('Your registered email is: ' . $notifiable->email)
            ->line('Your business code is: ' . $notifiable->branch->company->business_code )
            ->line('Keep this code safe as you will need it everytime you login into ' . env('APP_NAME') . ' application');
    }

    /**
     * Get the smsrepresentation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toSms($notifiable)
    {

        return $notifiable->phone
            ?
            (new SmsMessage())
                ->setTo($notifiable->phone)
                ->setLine('Dear ' .  ucfirst($notifiable->first_name) . ucfirst($notifiable->last_name))
                ->setLine('Your account has been successfully created')
                ->setLine('Registered email is: ' . $notifiable->email)
                ->setLine('Business code is: ' . $notifiable->branch->company->business_code)
                ->send()
            :
            null;
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
