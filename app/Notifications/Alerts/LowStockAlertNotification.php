<?php

namespace App\Notifications\Alerts;

use App\Channels\SmsChannel;
use App\Channels\SmsMessage;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $product;
    public $branch;
    public $remaining_qty;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
        $this->branch = $product->branch;
        $this->remaining_qty = $product->latest_inventory ? $product->latest_inventory->available_quantity:0 ;
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
                    ->subject('Urgent: Low Stock Alert - '. Carbon::now()->toFormattedDayDateString())
                    ->greeting('Hello ' . ucwords($notifiable->first_name) .',' )
                    ->line("Quick heads up: Inventory for " .ucwords($this->product->name).'  is running critically low, with only '. $this->remaining_qty .' '. $this->product->units_of_measure . ' remaining at '.ucwords($this->branch->name).'.');
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
            ->setTo($notifiable->phone)
            ->setLine('Low Stock Alert - '. Carbon::now()->toFormattedDayDateString())
            ->setLine('Hello '. ucwords($notifiable->first_name) . ', ')
            ->setLine('Just a heads-up: ')
            ->setLine('Branch: '. ucwords($this->branch->name) )
            ->setLine('Product: ', ucwords($this->product->name) )
            ->setLine('Remaining: '. $this->remaining_qty )
            ->setLine('Time to restock! Bizkit POS has got your back')
            ->send()
            :
            null;
    }
}
