<?php

namespace App\Notifications\Supplier;

use App\Channels\SmsChannel;
use App\Channels\SmsMessage;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentProcessedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $payment;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [ 'mail', SmsChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $company = $notifiable->branch ->company;

        return $notifiable->email ?
        (new MailMessage)
                    ->greeting('Dear '.$notifiable->first_name)
                    ->line("Your Payment of KES  $this->payment->amount_paid  has been processed"."at  ucwords($company->name)")
                :null;
    }

    
    public function toSms($notifiable)
    {
        $company = $notifiable->branch ->company;
        return $notifiable->phone
            ?
            (new SmsMessage())
            ->setTo($notifiable->phone)
            ->setLine('Hello ' . ucwords($notifiable->first_name) . ',')
            ->setLine("Your Payment of KES  $this->payment->amount_paid "."  has been processed at  ucwords($company->name) ")
            ->send()
            :
            null;
    }
}
