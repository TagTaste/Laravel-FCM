<?php

namespace App\Http\Controllers\Api;


use App\Collaborate;
use App\Job;
use Illuminate\Http\Request;

class SuggestionEngineController extends Controller
{
    public function suggestion(Request $request,$modelName)
    {
        $key = 'suggested:'.$modelName.':'.$request->user()->profile->id;

        $modelIds = \Redis::sMembers($key);
        if($modelName == 'profile')
        {
            $profileIds = [];
            foreach ($modelIds as $key=>$modelId)
            {
                if($modelId == '')
                {
                    unset($modelIds[$key]);
                    continue;
                }
                $profileIds[$key] = "profile:small:".$modelId ;
            }

            if(count($profileIds)> 0)
            {
                $suggestedProfiles = \Redis::mget($profileIds);
            }
            foreach($suggestedProfiles as &$profile){
                if(is_null($profile)){
                    continue;
                }
                $profile = json_decode($profile);
                $key = "following:profile:".$request->user()->profile->id;
                $profile->isFollowing =  \Redis::sIsMember($key,$profile->id) === 1;
            }
            $this->model = $suggestedProfiles;
            return $this->sendResponse();
        }
        elseif($modelName == 'company')
        {
            $companyIds = [];
            foreach ($modelIds as $key=>$modelId)
            {
                if($modelId == '')
                {
                    unset($modelIds[$key]);
                    continue;
                }
                $companyIds[$key] = "company:small:".$modelId ;
            }

            if(count($companyIds)> 0)
            {
                $suggestedCompanies = \Redis::mget($companyIds);
            }
            foreach($suggestedCompanies as &$company){
                if(is_null($company)){
                    continue;
                }
                $company = json_decode($company);
                $key = "following:profile:".$request->user()->profile->id;
                $company->isFollowing =  \Redis::sIsMember($key,"company.".$company->id) === 1;
            }
            $this->model = $suggestedCompanies;
            return $this->sendResponse();
        }
        elseif($modelName == 'job')
        {
            $jobIds = [];
            foreach ($modelIds as $key=>$modelId)
            {
                if($modelId == '')
                {
                    unset($modelIds[$key]);
                    continue;
                }
                $jobIds[$key] = $modelId ;
            }

            $this->model = Job::whereIn('id',$jobIds)->where('state',1)->get();
            return $this->sendResponse();
        }
        else{
            $collaborateIds = [];
            foreach ($modelIds as $key=>$modelId)
            {
                if($modelId == '')
                {
                    unset($modelIds[$key]);
                    continue;
                }
                $collaborateIds[$key] = "company:small:".$modelId ;
            }
            $this->model = Collaborate::whereIn('id',$collaborateIds)->where('state',1)->get();
            return $this->sendResponse();
        }
    }
}