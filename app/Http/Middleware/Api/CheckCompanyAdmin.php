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
        if($request->isMethod("GET")){
            return $next($request);
        }
        
        $id = (int) ($request->companyId ?: $request->company);
        
        $checkAdmin = CompanyUser::where("company_id",$id)->where('profile_id', $request->user()->profile->id)->exists();
        
        if (!$checkAdmin) {
            return response()->json(['error' => "User does not belong to this company."],401);
        }

        return $next($request);
    }
}
