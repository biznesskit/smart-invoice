<?php

namespace App\Models\Landlord;

use App\Models\Company;
use App\Models\Landlord\Meta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
class Tenant extends Model
{
    // use SoftDeletes;
    use  HasFactory;
    use Notifiable;
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'landlord';
    

    protected $guarded = [];

   

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subscription_start_date' => 'datetime',
        'subscription_end_date' => 'datetime',
    ];

    
    public function configure()
    {
        config([
            'database.connections.tenant.database' => $this->database,
            'database.connections.tenant.host' => $this->database_host,
        ]);

        DB::purge('tenant');
        DB::reconnect('tenant');
        Schema::connection('tenant')->getConnection()->reconnect();

        return $this;
    }

    
    public function use()
    {
        app()->forgetInstance('tenant');
        app()->instance('tenant', $this);

        return $this;
    }

    

    public function account()
    {
        return $this->hasMany(Account::class);
    }
    public function companies()
    {
        return $this->hasMany(Company::class, 'business_code', 'business_code');
    }
    
    public function latest_account()
    {
        return $this->hasOne(Account::class)->latest();
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Route notifications for the mail channel.
     */
    public function routeNotificationForMail($notification)
    {        
        return $this->business_email; 
    }
    /**
     * Route notifications for the sms channel.
     */
    public function routeNotificationForSms($notification)
    {        
        return $this->business_email; 
    }

    public function meta() 
    {
        return $this->morphMany(Meta::class, 'metaable');
    }

   
}

