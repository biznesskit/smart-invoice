<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Helpers\AuthUserHelper;
use App\Helpers\Utilities;
use App\Http\Controllers\Controller;
use App\Models\Landlord\Tenant;
use App\Models\User;
use App\Notifications\User\SendResetPasswordOTPCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{

/**
 * Forgot business code
 */
public function forgotBusinessCode(Request $request){
    $request->validate([
        'business_email' => 'required'
    ]);
        $tenant = Tenant::where('business_email', $request->business_email)->first();
        if(is_null($tenant))$tenant = Tenant::where('business_phone', Utilities::cleanPhoneNumber($request->business_email))->first();

        if (is_null($tenant))
            return response()->json([
                'success' => false,
                'message' => ' Account not found',
                'data' => []
            ], 404); 

        $tenant->configure()->use();

        return response()->json([
            'success' => true,
            'message' => ' Account found',
            'data' => $tenant
        ], 200);    
        }


    /**
     * Forgot password
     */
    public function forgotPassword(Request $request){
        $request->validate([
            'email'=> 'required',
            'company_pin'=> 'nullable|string',
            'business_code' => 'nullable|numeric|digits:5'
        ]);
$tenant = Tenant::where('kra_pin', $request->company_pin)->first();
$tenant = $tenant ? $tenant : Tenant::where('business_code', $request->business_code)->first();
        if($tenant):
            $tenant->configure()->use();

            $user = User::where('email', $request->email)->first();
            if(is_null($user)) $user = User::where('phone', Utilities::cleanPhoneNumber($request->email))->first();
            if (is_null($user)) $user = User::where('id_no', $request->email)->first();    

            if($user): 
                $otp= rand(1000,9999);
                $user->update(['otp_code'=>$otp]);
                
                $user->notify( new SendResetPasswordOTPCode($user));
                return response()->json([
                    'success' => true,
                    'message'=> ' OTP code sent to registered email and phone number',
                    'data' => []
                ],200);
            endif;
        endif;

        return response()->json([
            'success' => false,
            'message' => ' Account not found',
            'data' => []
        ], 404); 
    }


    // Process reset password OTP
    public function forgotPasswordOTPConfirmation(Request $request){
        $request->validate([
            'otp_code'=> 'numeric|required',
            'business_code'=>'nullable|numeric',
            'company_pin'=>'nullable|string',
            'email'=>'required',
            'password' => 'required|string|min:4|confirmed',
            'password_confirmation' => 'required|string'
        ]);

        $tenant = Tenant::where('kra_pin', $request->company_pin)->first();
        $tenant =   $tenant ? $tenant : Tenant::where('business_code', $request->business_code)->first();

        if($tenant):
            $tenant->configure()->use();
            $user= User::where('email', $request->email)->first();
            $user = $user?$user : User::where('phone', Utilities::cleanPhoneNumber($request->email))->first();
            if($user):
                if($user->otp_code == $request->otp_code):
                    $user->update(['otp_code'=>null, 'password'=>Hash::make($request->password)]);
            $token = AuthUserHelper::getAuthToken($user);

                    return response()->json([
                        'success' => true,
                        'message' => ' Account recovered and password changed',
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
            endif;
        endif;

        return response()->json([
            'success' => false,
            'message' => 'Wrong OTP code',
            'data' => []
        ], 404); 
    }

    /**
     * Reset password 
     */
    public function resetPassword(Request $request){
       $request->validate([
            'new_password'=> 'required|string|min:4|confirmed',
            'new_password_confirmation'=> 'required|string',
            'email' => 'required|email',
            'business_code' => 'nullable|numeric|digits:5',
            'company_pin' => 'nullable|string'
        ]);

      $tenant = Tenant::where('kra_pin', $request->company_pin)->first();
        $tenant =   $tenant ? $tenant : Tenant::where('business_code', $request->business_code)->first();

        if($tenant):
            $tenant->configure()->use();
            if ($user = User::with('permissions')->where('email', $request->email)->first()) :
                $user->update([ 'password' => Hash::make($request->new_password)    ]);
                $token = $user->createToken('access_token')->plainTextToken;
                $user->role = $user->getRoleNames()->first();

                return response()->json([
                    'success' => true,
                    'message' => ' Password changed',
                    'data' =>  collect($user)->only([
                    'first_name',
                    'last_name',
                    'email',
                    'phone',
                    'username'
                ]),
                        'token' => $token,
                    
                ], 200);
            endif;
        endif;

        return response()->json([
            'success' => false,
            'message' => ' Account not found',
            'data' => []
        ], 404); 
    }

    public function changePassword(Request $request){
        $request->validate([
            'password' => 'required|string|min:4|confirmed',
            'password_confirmation' => 'required|string',
        ]);

        $user = $request->user();
        $user->update(['password'=>Hash::make($request->password)]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed',
            'data' => []
        ], 200); 
    }

    public function resendOTP(Request $request){
        return $request->user();
    }
   
}
