<?php

namespace App\Notifications\Transfer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransferItemRejected extends Notification implements ShouldQueue
{
    use Queueable;

    public $item, $destinationBranch;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($item, $destinationBranch)
    {
        $this->item = $item;
        $this->destinationBranch = $destinationBranch;
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
        ->subject('Item Request Rejected')
                    ->greeting('Hello '. $notifiable->first_name)
                    ->line('Item '. ucwords($this->item->name) . ' request from ', ucwords($this->destinationBranch->name) . ' has been rejected.')
                    ->line('Reason: ');
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
