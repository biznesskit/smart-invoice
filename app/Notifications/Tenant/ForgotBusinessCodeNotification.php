<?php

namespace App\Notifications\Tenant;

use App\Channels\SmsChannel;
use App\Channels\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ForgotBusinessCodeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $tenant;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [ SmsChannel::class, 'mail'];

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
                    ->subject('Bizkit Account Recovery')
                    ->greeting("Hello " . ucwords($notifiable->name))                 
                    ->line('This email is in response to an account recovery request made through your account. ')
                    ->line('Your business code is: ' . $notifiable->business_code)
                    ->line("If you didn't initiate this, please contact us immediately!");

    }

    /**
     * Get the smsrepresentation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toSms($notifiable)
    {
        return $notifiable->business_phone
            ?
            (new SmsMessage())
            ->setTo($notifiable->business_phone)
            ->setLine( ucwords($notifiable->name) . ',')

            ->setLine('Your business code is: ' . $notifiable->business_code)
            ->setLine(env('APP_NAME'))
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
