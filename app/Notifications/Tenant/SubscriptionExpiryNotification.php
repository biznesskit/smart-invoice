<?php

namespace App\Notifications\Tenant;

use App\Channels\SmsChannel;
use App\Channels\SmsMessage;
use App\Helpers\ExpoServerLink;
use App\Jobs\Expo\SendExpoNotificationJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class SubscriptionExpiryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user)
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
                ->line( ucwords($notifiable->first_name .' ' . $notifiable->last_name))
                ->line('Your subscription is expiring in less than 3 days')
                ->line('Please consider renewing your subscription to ensure a continous Bizkit experience. ');
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
            ->setLine('Dear ' . ucfirst($notifiable->first_name ). ',')
            ->setLine('Your ' . env("APP_NAME", 'Bizkit') . " subscription will expire in 3 days")
            ->setLine('Consider renewing for a contiuous experience')
            ->send()
            :
            null;
    }
    public function toExpoPush($notifiable)
    {
        if (!$notifiableID = $notifiable->expo_device_id) return;

        $data = [
            "badge" => 1,
            'to' => $notifiableID,
            "title" => 'Subscription',
            "body" =>  'Expiry reminder',
            "data" => [
                "type" => 'subscription',
                "sub_type" => 'expiry reminder',
                "payload" => ucwords($notifiable->first_name .' ' . $notifiable->last_name ) . ` , \n Your subscription expires in less than 3 days. \nPlease consider renewing your subscription to ensure a contious bizkit experience`
            ]
        ];

        SendExpoNotificationJob::dispatch($data); 
    }

}
