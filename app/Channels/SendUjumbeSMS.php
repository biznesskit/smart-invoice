<?php

namespace App\Channels;

use function config;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use function implode;

class SendUjumbeSMS
{
    public $recipients = [];
    public $url= 'https://ujumbesms.co.ke/api/messaging';

    public static function to(array $recipients)
    {
        $instance = new static();
        $instance->recipients = $recipients;
        return $instance;
    }

    public function message($message)
    {
        $payload = [
            'data' => [
                [
                    'message_bag' => [
                        'numbers' => implode(",", $this->recipients),
                        'message' => $message,
                        'sender' => env('SMS_PROVIDER_SENDER_ID', 'BIZNESSKIT'),
                    ],
                ],
            ],
        ]; 

        return $this->request(  $payload);        
    }

    public  function request(  array $parameters = [])
    {
      return  $response = (new Client())->post( ltrim($this->url, '/'), [
            'headers' => [
                'X-Authorization' => env('SMS_PROVIDER_API_KEY', 'OTY2NjZkZjRhNzZmYzJhZTRhYjQyZmJlMGEzN2Y1'),
                'email' => env('SMS_PROVIDER_REGISTERED_EMAIL','biznesskitltd@gmail.com')
            ],
            'json' => $parameters,
        ]);

    }
}
