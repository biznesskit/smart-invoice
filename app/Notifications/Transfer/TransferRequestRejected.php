<?php

namespace App\Notifications\Transfer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransferRequestRejected extends Notification implements ShouldQueue
{
    use Queueable;

    public $transfer;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct($transfer)
    {
        $this->transfer = $transfer;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
        ->subject('Stock Request Rejected')
            ->greeting('Hello '. ucwords($notifiable->first_name))
            ->line('Your stock request to '.ucwords($this->transfer->destination_branch->name).' has been rejected')
            ->line("Reason: ".$this->transfer->request_rejected_reason);
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
