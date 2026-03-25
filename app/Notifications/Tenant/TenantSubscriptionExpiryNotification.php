<?php

namespace App\Notifications\Tenant;

use App\Channels\SmsChannel;
use App\Channels\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TenantSubscriptionExpiryNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $remaining_days;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($remaining_days)
    {
        $this->remaining_days = $remaining_days;
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
                        ->subject("Your BizKit Subscription Expires in $this->remaining_days Days")
                        ->greeting('Hello ' . ucfirst($notifiable->name ). ',')
                        ->line('To renew, visit the BizKit dashboard and tap "Settings." Click on "Subscription" to view the remaining days before the upgrade. Tap “Top up” to purchase your monthly subscription.')
                        ->line('If you have any questions or need further assistance with your subscription, feel free to contact our support team at info@bizkitpos.com or (+254) 759129876.');

       
    }

    public function toSms($notifiable)
    {
        return $notifiable->business_phone
            ?
            (new SmsMessage())
            ->setTo($notifiable->business_phone)
            ->setLine('Dear ' . ucfirst($notifiable->name ). ',')
            ->setLine("You subscription will expire in $this->remaining_days day(s)")
            ->setLine('Please renew your subscription to avoid service disruptions.')
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
