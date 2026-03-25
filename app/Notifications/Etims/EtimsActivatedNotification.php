<?php

namespace App\Notifications\Etims;

use App\Models\Company;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EtimsActivatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public  $company;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( Company $company)
    {
         $this->company = $company;
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
        ->subject('KRA eTIMS Activated')
        ->greeting('Hello '. ucwords($notifiable->first_name))
                    ->line('Etims integration for ' . ucwords($this->company->name). ' has been sucessful')
                    ->line('');
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
