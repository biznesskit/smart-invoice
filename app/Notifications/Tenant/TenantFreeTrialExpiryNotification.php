<?php

namespace App\Notifications\Tenant;

use App\Channels\SmsChannel;
use App\Channels\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TenantFreeTrialExpiryNotification extends Notification implements ShouldQueue
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
        if( $this->remaining_days > 5 )
            return (new MailMessage)
                        ->subject("Your BizKit Free Trial Ends Soon")
                        ->greeting('Hello ' . ucfirst($notifiable->name ). ',')
                        ->line("We hope you’re enjoying the BizKit App. We wanted to give you a heads-up that your free trial period is quickly coming to an end. ")
                        ->line("But don't worry, there's still time to explore all the powerful features and benefits our premium plan has to offer.")
                        ->line('To upgrade, visit the BizKit dashboard and tap "Settings." Click on "Subscription" to view the remaining days before the upgrade. Tap “Top up” to purchase your monthly subscription.')
                        ->line('If you have any questions or need further assistance with your subscription, feel free to contact our support team at info@bizkitpos.com or (+254) 759129876.');
        else 
            return (new MailMessage)
                        ->subject("Your BizKit Free Trial Ends in $this->remaining_days Day(s)")
                        ->greeting('Hello ' . ucfirst($notifiable->name ). ',')
                        ->line("We hope you’re enjoying the BizKit App. To continue experiencing the full potential of BizKit and to avoid any interruption in service, we encourage you to upgrade to a paid subscription before your trial period expires.")
                        ->line('To upgrade, visit the BizKit dashboard and tap "Settings." Click on "Subscription" to view the remaining days before the upgrade. Tap “Top up” to purchase your monthly subscription.')
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
