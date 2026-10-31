<?php

namespace App\Notifications\Transfer;

use App\Channels\SmsChannel;
use App\Channels\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransferRequestDispatched extends Notification implements ShouldQueue
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
        ->subject('Stock  Dispatched')
            ->greeting('Hello '. ucwords($notifiable->first_name))
            ->line('Your stock request from '.ucwords($this->transfer->destination_branch->name).' has been dispatched')
            ->line('Please go to stock->transfer->outgoing requests to review!');
    }


    /**
     * Get the smsrepresentation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toSms($notifiable)
    {
        return $notifiable->phone
            ?
            (new SmsMessage())
            ->setTo($notifiable->phone)
            ->setLine('Your stock request from '.ucwords($this->transfer->originating_branch->name) .' has been dispatched' )
            ->setLine('Please go to stock outgoing requests section to review.')
            ->send()
            :
            null;
    }


}
