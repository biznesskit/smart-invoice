<?php

namespace App\Http\Middleware;

use App\Helpers\Moderator;
use App\Models\Landlord\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ModeratorHasToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = Moderator::getModeratorToken();
        if ($request->header('moderator_token') !== $token ) {
            return response()->json([
                'message'=>'Unauthenticated'
            ],401);
        }

        if($request->header('business_code')):
            $business_code = $request->header('business_code');
            if($tenant = Tenant::where('business_code', $business_code )->first()) $tenant->configure()->use();
        endif;            

        return $next($request);
    }
}
