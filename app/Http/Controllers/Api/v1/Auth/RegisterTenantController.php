<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Events\Business\newBusinessCreatedEvent;
use App\Helpers\AccessManagement;
use App\Helpers\AuthUserHelper;
use App\Helpers\NewTenantAccount;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\ValidateTenantRequest;
use Illuminate\Http\Request;
use App\Jobs\Tenant\CreateTenantJob;
use App\Helpers\Utilities;
use App\Http\Requests\Tenant\ValidateTenantUserRequest;
use App\Models\Branch;
use App\Models\Landlord\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterTenantController extends Controller
{

    public static function account_exists(Request $request)
    {
        $request->validate([
            'kra_pin' => 'required|string|min:10|max:10',
        ]);

        $tenant = Tenant::where('kra_pin',$request->kra_pin)->first();

        return response()->json([
            'success' => $tenant ? true : false,
            'message' => 'Tenant exists check',
            'data' =>  $tenant ? true : false
        ],200);

    }

    /**
     * Check if mail is valid
     */
    public function checkIfEmailIsAlreadyRegistered(Request $request){
        $request->validate([
            'business_email' => 'required|email'
        ]);

        $tenant= Tenant::where('business_email', $request->business_email)->first();

        if(is_null($tenant)) return response()->json([
                'success' => true,
                'message' => 'This Email is not registered and therefore available for use',
                'data' =>[]
            ], 200);

        else return response()->json([
            'success' => false,
            'message' => 'Email already registered',
            'data' =>[]
        ], 403);
    }

    /**
     * Check if mail is valid
     */
    public function checkIfPhoneIsAlreadyRegistered(Request $request){
        $request->validate([
            'phone' => 'required|numeric|min:9'
        ]);

        $phone = Utilities::cleanPhoneNumber($request->phone);

        $tenant= Tenant::where('business_phone', $phone )->first();

        if(is_null($tenant)) return response()->json([
                'success' => true,
                'message' => 'This Phone number is not registered and therefore available for use',
                'data' =>[]
            ], 200);

        else return response()->json([
            'success' => false,
            'message' => 'Phone number already registered',
            'data' =>[]
        ], 403);
    }


    /**
     * Register new tenant/business
    */
    public function storeTenant(ValidateTenantRequest $request)
    {
        $data = $request->validated();
        if( $tenant = Tenant::find($request->id) ) return $this->updateTenant($request, $tenant);
        $business_code = Utilities::getTenantBusinessCode();
        $data['business_code'] =  $business_code;
        $data['domain'] = Utilities::getTenantDomain($data['name']);
        $data['database'] = env('TENANT_DB_PREFIX','tenant_esb').'_'.str_replace('-','_',$data['domain']);
        $data['database'] = str_replace(' ', '_', $data['database']);
        $data['database_host'] = isset($data['database_host']) ? $data['database_host'] :  env('DEFAULT_TENANT_DATABASE_HOST', 'localhost');
        $data['agent_code'] = Utilities::generateTenantAgentCode();
        $data['tracking_number'] = $request->tracking_number;
        $data['country'] = 'Kenya';
        $data['tracking_number'] = $data['tracking_number'] ? $data['tracking_number'] : rand(1000,100000);

        $tenant = Tenant::create($data);

        CreateTenantJob::dispatchSync($tenant, $data);

        return response()->json([
            'success' => true,
            'message' => 'Business account created',
            'data' => collect($tenant)->only([
        'name',
        'business_type',
        'tracking_number',
        'kra_pin',
        'country',
        'business_phone',
    ])
        ], 201);
    }

    public function updateTenant(ValidateTenantRequest $request,Tenant $tenant)
    {
        if(is_null($tenant)) return response()->json([
            'success' => false,
            'message' => 'Tenant account not found',
            'data' => []
        ],404);

        $data = $request->validated();

        $tenant->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Tenant updated',
            'data' => $tenant
        ], 201);
    }


    /**
     * Create tenant
     */
    public function createTenantUser(ValidateTenantUserRequest $request)
    {
        $data = $request->validated();
        $roleName='super-admin';
        // $tenant = Tenant::find($request->tenant_id);
        $tenant = Tenant::where('kra_pin',$request->company_pin)->first();
        $tenant = $tenant?$tenant: Tenant::where('kra_pin',$request->kra_pin)->first();
        $tenant = $tenant ? $tenant: Tenant::where('tracking_number',$request->tracking_number)->first() ;
        if(is_null($tenant)) return response()->json([
            'success' => false,
            'message' => 'Business  account not found',
            'data' => []
        ],404);

        $tenant->configure()->use();
        $data['data'] =   $request->hashed ? $request->password :  Hash::make( $request->password);
        $data['username'] = $request->first_name . rand(10,100);

        //check if migration happend
        if( ! NewTenantAccount::tenantDBReady($tenant) ) $this->prepareTenantDatabase($tenant,$request->all());

        $branch = Branch::first();
        if (is_null($branch)) return response()->json([
            'success' => false,
            'message' => 'Branch not found',
            'data' => []
        ], 404);

        //Update tenant record
        $tenant->update([
            'business_email'=> $data['email'],
            'business_phone'=> Utilities::cleanPhoneNumber($data['phone']),
            'subscription_active' => 1,
            'subscription_amount' => 45,
            'subscription_start_date' => now(),
            'subscription_end_date' => now()->addDays(1)
        ]);

        $company = $branch->company;

        $company->update([
            'kra_pin'=> $tenant->kra_pin
        ]);
        $branch->update([
            'kra_pin'=> $tenant->kra_pin ,
            'solution_type'=> '04'
        ]);

        $user = NewTenantAccount::createUser($data, $branch);
        $user->assignRole( $roleName );
        AccessManagement::createRoleAssignUser( $roleName, $user);

        $user = AuthUserHelper::getAuthUser($user);
        $token =   AuthUserHelper::getAuthToken($user);



        return response()->json([
            'success' => true,
            'message' => 'Record  created',
            'data' => collect($user)->only([
                            'first_name' ,
                            'last_name' ,
                            'email' ,
                            'phone' ,
                            'username'
                    ]),
         'token' => $token
        ], 201);
    }


    /**
     * Delete tenant
     */
    public function destroy(Request $request, Tenant $tenant){
        if(is_null($tenant)) return;

        $tenant->forceDelete();


        return response()->json([
            'success'=> true,
            'message' => 'Tenant deleted successfuly',
            'data' =>[]
        ],200);
    }


    /**
     * Delete tenant
     */
    public function parmanentlyDeleteTenant(Request $request,  $tenant_id){

       $tenant =   Tenant::withTrashed()->find($tenant_id);

        if(is_null($tenant)) return response()->json([
            'success' => false,
            'message' => 'Tenant not found',
            'data' => []
        ],404);

       DB::statement("DROP DATABASE $tenant->database");

        $tenant->forceDelete();


        return response()->json([
            'success'=> true,
            'message' => 'Tenant account parmanently deleted and all database records destroyed',
            'data' =>[   ]
        ],200);
    }

    private function prepareTenantDatabase(Tenant $tenant, $data)
    {
        NewTenantAccount::migrateTenantDB($tenant);
        $company = NewTenantAccount::createCompany($data, $tenant);
        NewTenantAccount::createBranch($data, $company);
    }

}
