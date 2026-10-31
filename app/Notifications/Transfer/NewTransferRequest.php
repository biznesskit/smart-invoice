<?php

namespace App\Notifications\Transfer;

use App\Channels\SmsChannel;
use App\Channels\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewTransferRequest extends Notification implements ShouldQueue
{
    use Queueable;

    public $transfer, $originBranch;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($transfer, )
    {
        $this->transfer = $transfer;
        $this->originBranch = $transfer->originating_branch;
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
            ->subject('Stock Transfer Request')
            ->greeting('Hello '. ucwords($notifiable->first_name))
            ->line( ' You have recieved  a new stock request from ' . ucwords($this->originBranch->name).' branch')
            ->line(' Please go to Stock->Transfer->incoming requests section to review the request');
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
            ->setLine('You have received a new stock transfer request from '.ucwords($this->originBranch->name) )
            ->setLine('Please go to stock incoming requests section to review.')
            ->send()
            :
            null;
    }
}
