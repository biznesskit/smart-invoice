<?php

namespace App\Notifications\Subscription;

use App\Channels\SmsChannel;
use App\Channels\SmsMessage;
use App\Jobs\Expo\SendExpoNotificationJob;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class SubscriptionPackageActivatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $tenant, $addons;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($tenant, $addons)
    {
        $this->tenant = $tenant;
        $this->addons = $addons;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', SmsChannel::class, $this->toExpoPush($notifiable)];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $pivot = count($this->tenant->packages) ? $this->tenant->packages[0]->pivot:[];
        return (new MailMessage)
                ->subject('Subscription Activated')
                ->line(ucwords($notifiable->name))
                ->line('Your subscription  has been successfully activated')
                ->line('Subscription valid until '. Carbon::parse($pivot->expires_on)->toFormattedDateString(). '.')
               ->line('Activated addons:')
               ->line( $this->getAddons() )
                ->line('Expiry date: ' );
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
            ->setLine('Hello ' . ucwords($notifiable->first_name) . ',')
            ->setLine('Your subscription has been successfuly activated')
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


    /**
     * Send expo push notification
     */
    public function toExpoPush($notifiable)
    {
        // $tenant = $notifiable->branch->company->tenant;
        // $tenant->configure()->use();

        $notifiableIDs =[$notifiable->expo_push_token];

        // User::chunk(100, function ($users) use($notifiableIDs) {
        //     foreach ($users as $user) {
        //         if($user->expo_push_token) array_push($notifiableIDs, $user->expo_push_token);
        //     }

        $data = [
            "badge" => 1,
            'to' => $notifiableIDs,
            "title" => 'Subscription',
            "body" =>  'Subscription Activated',
            "data" => [
                "type" => 'subscription',
                "sub_type" => 'subscription_activated',
                "payload" => []
            ]
        ];

           SendExpoNotificationJob::dispatch($data);
        // });
    }

    public function getAddons(){
        $string = '';
        foreach ($this->addons as $item) {
            $pivot =$item->pivot;

           $string . $item->name . ' ';
            if($item->name == 'users' && $pivot)  $string .  ": ".$item->pivot->number_of_users;
            if($item->name == 'branches' && $pivot)  $string .  ": ".$item->pivot->number_of_branches;
        }
        return $string;
    }
}
