<?php

namespace App\Notifications\Subscription;

use App\Channels\SmsChannel;
use App\Channels\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionPaymentRecievedNotification extends Notification implements ShouldQueue
{
    use Queueable;

   public  $payment,$invoice;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($payment)
    {
        $this->payment = $payment;
        $this->invoice = $payment->invoice;
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
            ->subject("Your BizKit Receipt ". $this->invoice->invoice_number."")
            ->greeting("Hi ".ucwords($notifiable->name.", "))
            ->line('Your payment has been successfully received by BizKit.')
            ->line('Amount Paid: KES '. number_format($this->invoice->amount,2))
            ->line('Date Paid: '. $this->payment->created_at->format('d M Y H:i:s') )
            ->line('Valid For: 30 Days')
            ->line("Payment method: ".ucwords($this->payment->method)."")
            ->line('If you have any questions or need assistance with your subscription or using our app, reach out to our support team at info@bizkitpos.com or give us a call at +254759129876.');
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
            ->setLine('Dear '. ucwords($notifiable->name) . ',')
            ->setLine('Your payment of KES '.number_format($this->invoice->amount,2). ' has been recieved')
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
