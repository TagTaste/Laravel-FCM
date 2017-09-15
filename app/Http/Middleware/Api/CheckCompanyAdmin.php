<?php

namespace App\Http\Middleware\Api;

use App\CompanyUser;
use Closure;

class CheckCompanyAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $checkAdmin = CompanyUser::where("company_id",$request->companyId)->where('profile_id',$request->user()->profile->id)->exists();
        if(!$checkAdmin)
        {
            return response()->json(['error'=>"User does not belong to this company."]);
        }

        return $next($request);
    }
}
