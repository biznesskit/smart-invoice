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

class SendResetPasswordOTPCode extends Notification implements ShouldQueue
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
                    ->subject('Password Reset')
                    ->greeting('Hello '. ucwords($notifiable->first_name))  
                    ->line('You recently requested to reset your password for your BizKit account. ')                
                    ->line('Your password reset OTP is: '.$notifiable->otp_code)
                    ->line('Please use this code promptly to recover your password.');
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
