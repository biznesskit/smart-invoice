<?php

namespace App\Notifications\Transfer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DispachedItemRejected extends Notification implements ShouldQueue
{
    use Queueable;

    public $item, $originBranch;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($item, $originBranch)
    {
        $this->item = $item;
        $this->originBranch = $originBranch;
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
                    ->subject('Stock Item Rejected')
                    ->greeting('Hello '. $notifiable->first_name)
                    ->line('Item '. $this->item->name . ' dispatched to ', $this->originBranch->name . ' has been rejected. ')
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
