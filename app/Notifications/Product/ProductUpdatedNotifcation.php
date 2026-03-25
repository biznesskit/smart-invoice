<?php

namespace App\Notifications\Product;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class ProductUpdatedNotifcation extends Notification implements ShouldQueue
{
    use Queueable;

    public $product;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($product)
    {
        $this->product = $product;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $this->toExpoPush($notifiable);
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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

    /**
     * Expo push notification
     */
    public function toExpoPush($notifiable)
    {

        // $data = [
        //     "badge" => 1,
        //     "to" => $notifiable->expo_push_token,
        //     "title" => ucfirst($notifiable->first_name) . ' ' . ucfirst($notifiable->last_name),
        //     "body" => substr($this->product->name, 0, 10),
        //     "data" => [
        //         "type" => 'product',
        //         "payload" => json_encode($this->product)
        //     ]
        // ];

        // SendExpoNotificationJob::dispatch($data);
    }
}
