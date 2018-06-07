<?php

namespace App\Http\Controllers\Api;


use App\Collaborate;
use App\Job;
use Illuminate\Http\Request;

class SuggestionEngineController extends Controller
{
    private $relationships = [
        'job' => \App\Recipe\Job::class,
        'collaborate' => \App\Recipe\Collaborate::class
    ];
    public function suggestion(Request $request,$modelName)
    {
        $key = 'suggested:'.$modelName.':'.$request->user()->profile->id;

        $modelIds = \Redis::sMembers($key);

        if($modelName == 'job' || $modelName == 'collaborate')
        {
            $model = new $this->relationships[$modelName];
            $this->model = $model->whereIn('id',$modelIds)->where('state',1)->take(5)->inRandomOrder()->get()->toArray();

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
                $suggestedProfiles = [];
                if(count($profileIds)> 0)
                {
                    $suggestedProfiles = \Redis::mget($profileIds);
                }

                foreach($suggestedProfiles as $key=> &$profile){
                    if(is_null($profile)){
                        unset($suggestedProfiles[$key]);
                        continue;
                    }
                    $profile = json_decode($profile);
                    $key = "following:profile:".$request->user()->profile->id;
                    $profile->isFollowing =  \Redis::sIsMember($key,$profile->id) === 1;
                }
                $this->model = $suggestedProfiles->toArray();
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
                $suggestedCompanies = [];
                if(count($companyIds)> 0)
                {
                    $suggestedCompanies = \Redis::mget($companyIds);
                }
                foreach($suggestedCompanies as $key=> &$company){
                    if(is_null($company)){
                        unset($suggestedCompanies[$key]);
                        continue;
                    }
                    $company = json_decode($company);
                    $key = "following:profile:".$request->user()->profile->id;
                    $company->isFollowing =  \Redis::sIsMember($key,"company.".$company->id) === 1;
                }
                $this->model = $suggestedCompanies->toArray();
                return $this->sendResponse();
            }
        }
    }

    public function suggestionIgonre(Request $request,$modelName)
    {
        $key = 'suggested:'.$modelName.':'.$request->user()->profile->id;
        $ignoredId = $request->input('id');

        $this->model = \Redis::sRem($key,$ignoredId);

        return $this->sendResponse();
    }
}