<?php

namespace App\Http\Middleware\Api;

use App\Company;
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
        $company = Company::find($request->companyId);
        if(!$company)
        {
            return response()->json(['error' => "Company does not exist."]);
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST'||$_SERVER['REQUEST_METHOD'] === 'PATCH'||$_SERVER['REQUEST_METHOD'] === 'DELETE'||$_SERVER['REQUEST_METHOD'] === 'PUT') {
            $checkAdmin = CompanyUser::where("company_id", $request->companyId)->where('profile_id', $request->user()->profile->id)->exists();
            if (!$checkAdmin) {
                return response()->json(['error' => "User does not belong to this company."]);
            }
        }

        return $next($request);
    }
}
