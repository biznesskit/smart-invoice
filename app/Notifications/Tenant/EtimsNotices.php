<?php

namespace App\Notifications\Tenant;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class EtimsNotices extends Notification implements ShouldQueue
{
    use Queueable;
    public $etimsNotice;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($etimsNotice)
    {
        $this->etimsNotice = $etimsNotice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
        $noticeNo = isset($etimsNotice['noticeNo']) ?  $etimsNotice['noticeNo'] : null;
$data=[
    "title" => isset($this->etimsNotice['title']) ? $this->etimsNotice['title'] : null,
    "content" => isset($this->etimsNotice['cont']) ? $this->etimsNotice['cont'] : null,
    "external_link" => isset($this->etimsNotice['dtlUrl']) ? $this->etimsNotice['dtlUrl'] : null,
    "internal_link" => null,
];
//         $notc = $notifiable->notifications()->where('data', json_encode($data))->first();
//         Log::info($notc);
// if($notc) return[];
        return $data;
    }
}
