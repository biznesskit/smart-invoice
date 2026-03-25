<?php

namespace App\Helpers;

use App\Models\User;
use App\Helpers\ReportsHelper;
use App\Models\Branch;
use App\Models\Transfer;

class AuthUserHelper
{

    public static function getAuthUser(User $user)
    {
        $user = User::find($user->id);

        // $incoming_stock_transfer_requests = Transfer::where('destination_branch_id', $user->branch_id)->where('status', 'initiated')->orWhere('status', 'dispatched')->count();
        // $outgoing_stock_transfer_requests = Transfer::where('created_by', $user->id)->where('status', 'initiated')->orWhere('status', 'dispatched')->count();

        $user->branch->meta = $user->branch->meta;
        // $user->permissions =  $user->permissions()->pluck('name');
        // $user->roles = $user->roles()->pluck('name');
        // $user->role = $user->roles()->pluck('name');

        # ---------------------------------------------------
        // $user->collections = ReportsHelper::getTotalCollections($user, null, null, null); // to be depracated
        // $user->expenses = ReportsHelper::getTotalExpenses($user, null, null, null); // to be depracated
        // $user->data = self::getReportData($user); // replacement
        # ----------------------------------------------

        // $user->total_stock_requests_count = $incoming_stock_transfer_requests + $outgoing_stock_transfer_requests;
        // $user->incoming_stock_requests_count = $incoming_stock_transfer_requests;
        // $user->outgoing_stock_requests_count = $outgoing_stock_transfer_requests;
        return $user;
    }



    public static function getAuthToken(User $user)
    {
        $token = $user->currentAccessToken();
        $token = $token ? $token : $user->createToken('access_token')->plainTextToken;

        return $token;
    }

    
}
