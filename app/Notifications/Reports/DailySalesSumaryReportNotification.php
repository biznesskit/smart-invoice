<?php

namespace App\Notifications\Reports;

use App\Channels\SmsChannel;
use App\Channels\SmsMessage;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DailySalesSumaryReportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public  $branchIncomes;
    public $tenantName=NULL;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($branchIncomes = [])
    {
        $this->branchIncomes = $branchIncomes;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [ 'mail',SmsChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $this->getTenantName($notifiable);
        
        return (new MailMessage)
            ->subject("$this->tenantName Daily Sales Update - " . Carbon::now()->toFormattedDateString())
            ->greeting('Hello ' . ucwords($notifiable->first_name) . ',')
            ->line("Your daily sales report for $this->tenantName is here: ")
            ->line($this->populateArrayBreakdown())
            ->line('Thank you for using Bizkit POS. Keep up the great work!');
    }

    /**
     * Get the smsrepresentation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toSms($notifiable)
    {
        $this->getTenantName($notifiable);

        return $notifiable->phone
            ?
            (new SmsMessage())
            ->setTo($notifiable->phone)
            ->setLine('Hello ' . ucwords($notifiable->first_name) . ', ')
            ->setLine("Your daily sales report for $this->tenantName is here:")
            ->setLine($this->populateArrayBreakdown())
            ->send()
            :
            null;
    }

    public function populateArrayBreakdown()
    {
        $string = '';

        foreach ($this->branchIncomes as $branchIncome ) {
            if(!is_array($branchIncome )) continue;
            $string .= 'Branch: ' . ucwords($branchIncome['name']) .  ' Total Sales: ' . number_format($branchIncome['actual_sales']) . ', ';
        }
        return $string;
    }

    private function getTenantName($notifiable){
        if($this->tenantName) return $this->tenantName;
        $company = $notifiable->branch->company;
        $tenant = $company->tenant;
         $this->tenantName = ucwords($tenant->name); 
         return $this->tenantName;
    }


    
}
