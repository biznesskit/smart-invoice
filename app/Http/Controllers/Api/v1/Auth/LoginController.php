<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Helpers\AuthUserHelper;
use App\Helpers\Utilities;
use App\Http\Controllers\Controller;
use App\Models\Landlord\Tenant;
use App\Models\User;
use App\Notifications\User\LoginWithOTPCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class LoginController extends Controller
{
    /**
     * Login User
     */
    public function login(Request $request)
    {
        $request->validate([
            'business_code' => 'nullable|string',
            'kra_pin' => 'nullable|string',
            'company_pin' => 'nullable|string',
            'platform_pin' => 'nullable|string',
            'email' => 'required|email',
            'password' => 'required|string'
        ]);


        $tenant = Tenant::where('kra_pin', $request->company_pin)->first();
        $tenant = $tenant?$tenant : Tenant::where('kra_pin', $request->company_pin)->first();
        $tenant =  $tenant?$tenant :  Tenant::where('kra_pin', $request->platform_pin)->first();
        $tenant =  $tenant ? $tenant : Tenant::where('kra_pin', $request->kra_pin)->first();
        $tenant = $tenant ? $tenant : Tenant::where('business_code', $request->business_code)->first();

        if (!$tenant) return response()->json([
            'success' => false,
            'message' => 'Wrong business code',
            'data' => []
        ], 404);

        $tenant->configure()->use();

        //Login with email, phone or ID no
        $user = User::where('email', $request->email)->first();
        if (empty($user)) $user = User::where('phone', Utilities::cleanPhoneNumber($request->email))->first();
        if (empty($user)) $user = User::where('id_no', $request->email)->first();

        if (empty($user)) return response()->json([
            'success' => false,
            'message' => 'Credentials do not match',
            'data' =>  []
        ], 404);
        $is_password_correct =  Hash::check($request->password, $user->password);
     


        if ($is_password_correct) :

            $user = AuthUserHelper::getAuthUser($user);
            // $token = $user->createToken('access_token')->plainTextToken;
            $token = AuthUserHelper::getAuthToken($user);


            return response()->json([
                'success' => true,
                'message' => 'Login Successful',
                'data' =>  collect($user)->only([
                    'first_name',
                    'last_name',
                    'email',
                    'phone',
                    'username'
                ]),
                'token' => $token
            ], 200);
        endif;

        return response()->json([
            'success' => false,
            'message' => 'Incorrect password',
            'data' => []
        ], 404);

          
    }


    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        $logout_from_all_devices = $request->logout_from_all_devices ?? false;
        if ($logout_from_all_devices) $user->tokens()->delete();

        // LoginTimeTrackerHelper::recordLog($user, 'logout');

        return response()->json([
            'success' => true,
            'message' => 'User loged out',
            'data' => true
        ], 200);
    }

    public function authenticate_webportal(Request $request)
    {

        // $request->validate([
        //     'business_code' => 'required|numeric|digits:5',
        //     'username' => 'required',
        //     'password' => 'required|string'
        // ]);

        // $tenant = Tenant::where('business_code', $request->business_code)->first();

        // if (is_null($tenant)) return response()->json([
        //     'success' => false,
        //     'message' => 'Wrong business code',
        //     'data' => []
        // ], 200);

        // $tenant->configure()->use();


        // //login with email, phone or ID no
        // $user = User::where('email', $request->username)->first();
        // if (is_null($user)) $user = User::where('phone', Utilities::cleanPhoneNumber($request->username))->first();
        // if (is_null($user)) $user = User::where('id_no', $request->username)->first();

        // if (!$user) return response()->json([
        //     'success' => false,
        //     'message' => 'Credentials do not match',
        //     'data' => []
        // ], 200);

        // $is_password_correct =  Hash::check($request->password, $user->password);

        // if (!$is_password_correct)
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Credentials does not match',
        //         'data' => []
        //     ], 401);

        // $user->token = $user->createToken('access_token')->plainTextToken;
        // $branch = $user->branch;
        // $user->domain = $branch->company ? $branch->company->domain : null;
        // $user->business_code = $branch->company ? $branch->company->business_code : null;

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Success, user loged out',
        //     'data' => $user
        // ], 200);
    }


    public function sendLoginWithOTPCode(Request $request)
    {
        $request->validate([
            'business_code' => 'nullable|numeric|digits:5',
            'company_pin' => 'nullable|string',
            'email_or_phone' => 'required',
        ]);

            $tenant = Tenant::where('kra_pin', $request->company_pin)->first();
        $tenant =   $tenant ? $tenant : Tenant::where('business_code', $request->business_code)->first();

        if($tenant):
            $tenant->configure()->use();
            $user = User::where('email', $request->email_or_phone)->first();
            if (empty($user)) $user = User::where('phone', Utilities::cleanPhoneNumber($request->email_or_phone))->first();

            if ($user) :
                $otp = rand(1001, 9999);
                $user->update(['otp_code' => $otp]);
                $user->notify(new LoginWithOTPCode($user));
                return response()->json([
                    'success' => true,
                    'message' => 'Success OTP code sent',
                    'data' => []
                ], 200);
            endif;
        endif;

        return response()->json([
            'success' => true,
            'message' => 'Wrong credentials',
            'data' => []
        ], 401);
    }

    public function processLoginWithOTP(Request $request)
    {
        $request->validate([
            'business_code' => 'nullable|numeric|digits:5',
            'company_pin' => 'nullable|string',
            'email_or_phone' => 'required',

            'otp' => 'required|numeric|digits:4',
        ]);

   $tenant = Tenant::where('kra_pin', $request->company_pin)->first();
        $tenant =   $tenant ? $tenant : Tenant::where('business_code', $request->business_code)->first();

        if($tenant):
            $tenant->configure()->use();
            if ($user = User::where('otp_code', $request->otp)->first()) :
                $token = $user->currentAccessToken();
                $token = $token ? $token : $user->createToken('access_token')->plainTextToken;
                $user->update(['otp' => null]);
                return response()->json([
                    'success' => true,
                    'message' => 'Success, user logged in',
                    'data' =>  collect($user)->only([
                    'first_name',
                    'last_name',
                    'email',
                    'phone',
                    'username'
                ]),
                        'token' => $token,
                    
                ], 200);
            else :
                return response()->json([
                    'success' => false,
                    'message' => 'Wrong OTP code',
                    'data' => []
                ], 409);
            endif;

        endif;


        return response()->json([
            'success' => false,
            'message' => 'Unkown Credentials',
            'data' => []
        ], 401);
    }


    public function loginUserUsingCashierID(Request $request)
    {
        $request->validate(['cashier_id' => 'required|numeric|digits:4']);


        if (empty($cashier = User::where('cashier_id', $request->cashier_id)->first()))
            return response()->json([
                'success' => false,
                'message' => 'Cashier ID not recognised',
                'data' => []
            ], 404);

        $user = AuthUserHelper::getAuthUser($cashier->id);
        $token = AuthUserHelper::getAuthToken($user);


        return response()->json([
            'success' => true,
            'message' => 'Login using cashier ID successful',
            'data' => collect($user)->only([
                'first_name',
                'last_name',
                'email',
                'phone',
                'username'
            ]),
            'token' => $token
        ], 200);
    }


    /**
     * Get all  permissions in the system
     */
    public function getAllPermissions(Request $request)
    {
        $permissions = Permission::all();

        return response()->json([
            'success' => true,
            'message' => 'All system permisions returned',
            'data' =>  $permissions
        ], 200);
    }
}
