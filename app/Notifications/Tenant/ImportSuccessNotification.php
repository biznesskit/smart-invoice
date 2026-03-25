<?php

namespace App\Notifications\Tenant;

use App\Channels\SmsChannel;
use App\Channels\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;

class ImportSuccessNotification extends Notification implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    public $tenant;
    public $item_count;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($tenant)
    {
        $this->tenant = $tenant;
        // $this->item_count = $item_count;
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
            ->subject('Product Catalogue Uploaded ')
            ->greeting('Hello ' . ucwords($notifiable->name))
            ->line("Your product catalogue has been uploaded successfully.             
                    All items have been uploaded to your account.
                    Please access the 'Sell' option on your " . env('APP_NAME') . " mobile app to start selling");
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

            ->setLine(ucwords($notifiable->name) . "Your catalogue  has been uploaded to your account.")
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
