<?php

namespace App\Http\Controllers\Api;


use App\Collaborate;
use App\Job;
use Illuminate\Http\Request;

class SuggestionEngineController extends Controller
{
    private $relationships = [
        'job' => \App\Job::class,
        'collaborate' => \App\Collaborate::class
    ];
    public function suggestion(Request $request,$modelName)
    {
        $key = 'suggested:'.$modelName.':'.$request->user()->profile->id;

        $modelIds = \Redis::sMembers($key);

        if($modelName == 'job' || $modelName == 'collaborate')
        {
            $model = new $this->relationships[$modelName];
            $this->model = $model->whereIn('id',$modelIds)->where('state',1)->take(5)->inRandomOrder()->get();

            return $this->sendResponse();
        }
        else{
            if($modelName == 'profile')
            {
                $index = 0;
                $profileIds = [];
                foreach ($modelIds as $key=>$modelId)
                {
                    if($index>5)
                        break;
                    if($modelId == '')
                    {
                        unset($modelIds[$key]);
                        continue;
                    }
                    $profileIds[$key] = "profile:small:".$modelId ;
                    $index++;
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
            else
            {
                $companyIds = [];
                $index = 0;
                foreach ($modelIds as $key=>$modelId)
                {
                    if($index>5)
                        break;
                    if($modelId == '')
                    {
                        unset($modelIds[$key]);
                        continue;
                    }
                    $companyIds[$key] = "company:small:".$modelId ;
                    $index++;

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
        }
    }
}