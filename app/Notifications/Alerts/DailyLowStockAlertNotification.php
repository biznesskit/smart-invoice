<?php

namespace App\Notifications\Alerts;

use App\Channels\SmsChannel;
use App\Channels\SmsMessage;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DailyLowStockAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $products;
    public $branch;
    public $remaining_qty;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($products)
    {
        $this->products =  $products;
        // $this->branch = $product->branch;
        // $this->remaining_qty = $product->latest_inventory ? $product->latest_inventory->available_quantity:0 ;
   
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail',SmsChannel::class];
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
                    ->subject('Daily Low Stock Level Alert')
                    ->line('The following items are running out of stock')
                    ->line(ucwords($this->products));

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

    public function toSms($notifiable)
    {
        
        return $notifiable->business_phone
            ?
            (new SmsMessage())
            ->setTo($notifiable->phone)
            ->setLine('Daily Low Stock Alert - '. Carbon::now()->toFormattedDayDateString())
            ->setLine('Hello '. ucwords($notifiable->first_name) . ', ')
            ->setLine('Just a heads-up: ')
            ->setLine('The following items are running out of stock' )
            ->setLine(ucwords($this->products))
            ->send()
            :
            null;
    }
}
