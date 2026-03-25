<?php

namespace App\Notifications\Cashier;

use App\Channels\SmsChannel;
use App\Channels\SmsMessage;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyOwnerNewCashierRegistered extends Notification implements ShouldQueue
{
    use Queueable;

    public $cashier;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $cashier)
    {
        $this->cashier = $cashier;
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
        $branch = $this->cashier->branch;
        $company= $branch->company;
        return (new MailMessage)
                ->subject('New Cashier Registered')
                ->line('Dear '. ucfirst( $notifiable->first_name) .', ')                    
                ->line('A new cashier has been successfully registered at '. ucwords($company->name) .' ' . ucwords($branch->name))
                ->line('Cashier details: ')
                ->line( 'Name: '. ucwords($this->cashier->first_name .' '. ucwords($this->cashier->last_name)) )
                ->line( 'Email: ' . $this->cashier->email )
                ->line( 'Phone: ' . $this->cashier->phone );
    }

    /**
     * Get the smsrepresentation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toSms($notifiable)
    {
        $branch = $this->cashier->branch;
        $company = $branch->company;
        return $notifiable->phone
            ?
            (new SmsMessage())
            ->setTo($notifiable->phone)
            ->setLine(ucwords($this->cashier->first_name) .' ')
            ->setLine('Has been successfully registered as a casheir at '. ' ' )
            ->setLine($company-> name)
            ->setLine($branch-> name)
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
