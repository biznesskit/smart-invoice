<?php

namespace App\Notifications\Reistration;

use App\Channels\SmsChannel;
use App\Channels\SmsMessage;
use App\Models\Landlord\Tenant;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class NewBusinessAccountRegistered extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    public $tenant;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, Tenant $tenant)
    {
        $this->user= $user;
        $this->tenant = $tenant;
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
            ->subject("Welcome to ".env('APP_NAME', 'Bizkit')) 
            ->greeting('Hello '.  ucwords($this->tenant->name).',')           
            ->line("Congratulations! Your". env('APP_NAME', 'Bizkit')." account has been successfully created. Your account details are as follows:")
            ->line("Business code: " . $this->tenant->business_code)
            ->line("Registered business email:". $this->tenant->business_email )
            ->line("Thank you for choosing ".env('APP_NAME', 'Bizkit')." as your tax patner.")  ; 
            }

    /**
     * Get the smsrepresentation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toSms($notifiable)
    {
        return $this->user->phone
            ?
            (new SmsMessage())
            ->setTo($this->user->phone)
            ->setLine(ucwords($this->tenant->name))
            ->setLine(' account successfully registered ')
            ->setLine('Business code: '. $this->tenant->business_code)
            ->setLine('Business email: '. $this->user->email)
            // ->setLine('Cashier ID: '. $this->user->cashier_id)
            ->setLine(env('APP_NAME'))
            ->send()
            :
            null;
    }

    /**
     * Expo push notification
     */
    public function toExpoPush($notifiable)
    {
        // return ExpoMessage::create()
        //     ->badge(1)
        //     ->enableSound()
        //     ->title("Account activated")
        //     ->body("Your account has been activated");
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
