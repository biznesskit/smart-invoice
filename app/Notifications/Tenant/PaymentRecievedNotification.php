<?php

namespace App\Notifications\Tenant;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentRecievedNotification extends Notification  implements ShouldQueue
{
    use Queueable;

    public $tenant;
    public $payment;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($tenant, $payment)
    {
        $this->tenant = $tenant;
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
        // return ['mail'];
        
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
            ->subject('Invoice Payment Recieved')
            ->line(ucwords($this->tenant->name))
            ->line('Your invoice payment of ksh ' . $this->payment->amount_paid . ' via ' . ucwords($this->payment->payment_method) .' with reference no '. $this->payment->reference_number .' has been  successfully captured.');
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
