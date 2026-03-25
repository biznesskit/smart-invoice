<?php

namespace App\Notifications\Tenant;

use App\Channels\SmsChannel;
use App\Channels\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TenantFreeTrialExpiredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
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
                    ->greeting('Hello ' . ucfirst($notifiable->name ). ',')
                    ->subject("Your BizKit Free Trial Has Ended")
                    ->line('We hope you found value in using the BizKit App during your free trial period. To unlock the full potential of BizKit, we encourage you to upgrade to a paid subscription.')
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
            ->setLine('Your subscription has expired.')
            ->setLine('Please renew your subscription to continue using the service.')
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
