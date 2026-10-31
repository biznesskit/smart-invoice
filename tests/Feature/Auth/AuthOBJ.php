<?php

namespace Tests\Feature\Auth;

use App\Models\Landlord\Tenant;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuthOBJ extends TestCase
{
         public static function getFileName (){
                return  'auth_credentials.txt';  //file name
        }

        public static function getOBJ()
        {
                $jsonData =[];
            if (Storage::exists(self::getFileName())) 
                     $jsonData =json_decode( Storage::get(self::getFileName()), true);  // read auth obj from file

                return (object)  $jsonData;
        }
         public static function getCredentials()
         {
          return  self::getOBJ();
        }



         public static function storeCredentials(Array $authObj)
         {
            $jsonData = json_encode($authObj, JSON_PRETTY_PRINT);

            Storage::disk('local')->put(self::getFileName(), $jsonData );  // write auth obj to file
    
        }

        public static function getAuthHeaders()
        {
          $authobj =  self::getCredentials();

           $tenant = Tenant::first();
        $tenant ->configure()->use();
            
          return [
                'Content-Type' => 'application/json',
                'tenant_id' =>  $authobj->tenant_id,
                'business_code' => $authobj->business_code,
                'business-code' => $authobj->business_code,
               'Authorization' => 'Bearer ' .$authobj ->auth_token
            ];

        }

}