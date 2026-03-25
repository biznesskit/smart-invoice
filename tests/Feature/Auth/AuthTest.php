<?php

namespace Tests\Feature\Auth;

use App\Models\Landlord\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AuthTest extends TestCase
{
    protected $connectionsToTransact = ['landlord'];
    public $authCredentrialsFileName = 'auth_credentials.txt';


    public static function dropAllTenantsDatabases()
    {
        foreach (Tenant::all() as $tenant) :
            try {
                DB::statement("DROP DATABASE $tenant->database");
            } catch (\Throwable $th) {
                //throw $th;
            }
        endforeach;
    }
    /**
     * A basic feature test example.
     */
    public function test_register_tenant()
    {
        self::dropAllTenantsDatabases();

        Artisan::call('migrate:fresh', [
            '--force' => true,  // force on non interactive environments
            '--path' => 'database/migrations/landlord'
        ]);



        $reqParams = [
            'name' => 'test shop' . rand(10, 100),
            'country' => 'kenya',
            'business_type' => 'general shop'
        ];

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $response = $this->postJson('/v1/register-tenant', $reqParams, $headers);
        $response->assertStatus(201);

        $tenant = (object) $response->json()['data'];

        $this->createTenantUser($tenant);
    }


    protected function createTenantUser($tenant)
    {
        $reqParams = [
            'first_name' => 'super',
            'last_name' => 'admin',
            'name' => 'super admin',
            'email' => 'superadmin@email.com',
            'phone' => '070000000001',
            'password' => 'password',
            'tenant_id' => $tenant->id,
            'business_type' => $tenant->name,
        ];

        $response = $this->postJson('/v1/create-tenant-user', $reqParams);
        // Log::info(json_encode($response));
        $resData = (object) $response->json();
        $user = $resData->data;

        $authObj = [
            'tenant_id' => $tenant->id,
            'business_code' => $tenant->business_code,
            'auth_token' => $user['token'],
            'user' => $user['user']
        ];

        AuthOBJ::storeCredentials($authObj);

        $response->assertCreated();
    }

    public function test_can_log_in_user()
    {
        $reqParams = [
            'email' => 'superadmin@email.com',
            'password' => 'password',
            'business_code' => 10000,
        ];

        $response = $this->postJson('/v1/login', $reqParams);
        $resData = (object) $response->json();
        $user =  $resData->data;
        $branch = $user['user']['branch'];
        $company = $branch['company'];
        $tenant = $company['tenant'];

        $authObj = [
            'database_name' => $tenant['database'],
            'tenant_id' => $tenant['id'],
            'business_code' => $tenant['business_code'],
            'auth_token' => $user['token'],
            'user' => $user['user']
        ];

        AuthOBJ::storeCredentials($authObj);

        $response->assertOk();
    }

    public function test_can_forget_business_code()
    {
        $reqParams = [
            'business_email' => 'superadmin@email.com',
        ];

        $response = $this->postJson('/v1/forgot-business-code', $reqParams);

        $response->assertOk();
    }

    public function test_can_forget_password()
    {
        $reqParams = [
            'email' => 'superadmin@email.com',
            'business_code' => 10000,
        ];

        $response = $this->postJson('/v1/forgot-password', $reqParams);
        $response->assertOk();


        $resData = (object) $response->json();
        $otpParams = [
            'email' => 'superadmin@email.com',
            'business_code' => 10000,
            'otp_code' => $resData->data['otp_code']
        ];
        $response = $this->postJson('/v1/forgot-password-OTP-confirmation', $otpParams);

        $response->assertOk();
    }



    public function test_can_reset_password()
    {
        $reqParams = [
            'email' => 'superadmin@email.com',
            'business_code' => 10000,
            'new_password' => "password",
            'new_password_confirmation' => "password"
        ];

        $response = $this->postJson('/v1/reset-password', $reqParams);

        $response->assertOk();
    }
}
