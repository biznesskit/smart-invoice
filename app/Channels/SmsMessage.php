<?php


namespace App\Channels;

use AfricasTalking\SDK\AfricasTalking;

class SmsMessage
{
    protected string $senderID;
    protected string $apiKey;
    protected string $accessToken;
    protected string $email;
    protected string $url;
    protected array $lines;
    protected string $to;
    protected string $from;
    protected string $username;
    protected string $providerName;
    
    public function __construct($lines = [])
    {
         $this->username = env('AFRICAS_TAKING_USERNAME','bizkit');
         $this->lines = $lines;
         $this->senderID = env('SMS_PROVIDER_SENDER_ID', 'BIZNESSKIT');
         $this->apiKey = env('SMS_PROVIDER_API_KEY','4ce6a5c1270810f2466843d6e96e110eccba7a8f085883014b79cd1ad96e224e');
         $this->accessToken = env('SMS_PROVIDER_API_KEY','4ce6a5c1270810f2466843d6e96e110eccba7a8f085883014b79cd1ad96e224e');
         $this->email = env('SMS_PROVIDER_REGISTERED_EMAIL', 'biznesskitltd@gmail.com');
         $this->url = env('SMS_PROVIDER_URL', 'http://ujumbesms.co.ke/api/messaging');
         $this->providerName = env('SMS_PROVIDER_NAME', 'ujumbe_sms');
    }

    public function setLine($line = ''): self
    {
        $this->lines[] = $line;

        return $this;
    }

    public function setTo($to): self
    {
        $this->to = $to;

        return $this;
    }

    public function setFrom($from): self
    {
        $this->senderID = $from;
        return $this;
    }

    public function send()
    {
        $env =  env('APP_ENV', 'local');
        if( $env !== 'production') return;

       switch ($this->providerName) {
        case 'africas_talking':
            return $this->sendWithAfricasTalking();
            break;

        case 'ujumbe_sms':
            return $this->sendWithUjumbeSMS();
            break;
        
        default:
            return 'sms provider not recognized';
            break;
       }
    }

    public function sendWithAfricasTalking(){
        $AT       = new AfricasTalking($this->username, $this->apiKey);

        $sms      = $AT->sms();

        $result   = $sms->send([
            'to'      => $this->to,
            'message' => implode(' ',$this->lines),
            'from'    => $this->senderID
        ]);

        return $result;
    }

    public function sendWithUjumbeSMS(){

        $response =  SendUjumbeSMS::to([$this->to])->message(implode(' ', $this->lines));
        return $response;
   

    }

}
