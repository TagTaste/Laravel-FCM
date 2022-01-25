<?php

namespace App\Http\Middleware;

use App\Company;
use App\Surveys;
use Closure;

class manageApplicantsPermission
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

        $getModel = $request->get("REQUEST_URI");
        $profileId = $request->user()->profile->id;

        if (strpos($getModel, "surveys") !== 0) {
            $surveyId = $request->route('id');
            $getSurveyDetails = Surveys::where("id", $surveyId)->whereNull("deleted_at")->first();
            if (!empty($getSurveyDetails)) {
                $getCompanyId = (!empty($getSurveyDetails->company_id) ? $getSurveyDetails->company_id : null);

                if (!empty($getCompanyId)) {
                    $company = Company::find($getCompanyId);
                    $userBelongsToCompany = $company->checkCompanyUser($profileId);
                    if ($userBelongsToCompany && $company->is_premium == 1) {
                        return $next($request);
                    }
                } else {
                    if ($profileId == $getSurveyDetails->profile_id && $request->user()->profile->is_premium == 1) {
                        return $next($request);
                    }
                }
                // $isPremium = $request->user()->profile->is_premium;
            } else {
                // echo "collab";
                // return $next($request);    
            }
        }
        return response()->json(['data' => false, 'error' => 'permission_denied', 'status' => '403'], 403);
    }
}
