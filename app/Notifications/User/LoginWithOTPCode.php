<?php

namespace App\Notifications\User;

use App\Channels\SmsChannel;
use App\Channels\SmsMessage;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;

class LoginWithOTPCode extends Notification implements ShouldQueue
{
    use Queueable;
    use InteractsWithQueue;

    public $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
                    ->subject('Login with OTP')
                    ->line(ucwords($notifiable->first_name.' '. $notifiable->last_name))                  
                    ->line('Your  OTP code is: ')
                    ->line($notifiable->otp_code)
                    ->line('Use this one time password to login into your account');
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

            ->setLine('Your OTP code is: ' . $notifiable->otp_code)

            ->send()
            :
            null;
    }
}
