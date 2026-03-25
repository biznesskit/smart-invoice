<?php

namespace App\Notifications\Cashier;

use App\Channels\SmsChannel;
use App\Channels\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CashierCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    
    public $cashier;
    public $password;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($cashier, $password)
    {
       $this->cashier=$cashier;
       $this->password=$password;
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
                    ->subject('Your '.ucwords($notifiable->branch->company->name) . ' account')
                    ->line(ucwords($notifiable->first_name .' '. $notifiable->last_name ))
                    ->line('Your account hass been successfully created at '. ucwords($notifiable->branch->company->name) .' '. ucwords($notifiable->branch->name))
                    ->line('Your login credentials are: ')
                    ->line('Business code: '. $notifiable->branch->company->business_code)
                    ->line('Cashier ID: '. $notifiable->cashier_id)
                    ->line('Email: '. $notifiable->email)
                    ->line('Phone: '. $notifiable->phone)
                    ->line('Password: '. $this->password)
                    ->line('Use the above credentials to login');
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
            ->setLine('Hello ' . ucwords($notifiable->first_name) . ', ')
            ->setLine('Your credentials for : ' . $notifiable->branch->company->name . ' are: ')
            ->setLine('Business code: ' . $notifiable->branch->company->business_code)
            ->setLine('Password: ' . $this->password)
            // ->setLine('Email: ', $notifiable->email )
            // ->setLine('Cashier ID: '. $notifiable->cashier_id)
       
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
