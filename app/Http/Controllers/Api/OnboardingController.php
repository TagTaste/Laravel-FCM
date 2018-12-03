<?php

namespace App\Http\Controllers\Api;

use App\Onboarding;
use App\Recipe\Profile;
use App\SearchClient;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function skills()
    {
        $this->model = \DB::table('onboarding')->select('value')->where('key', 'skills')->inRandomOrder()->take(27)->get();
        return $this->sendResponse();

    }

    public function autoCompleteSkills(Request $request) {
        $term = $request->get('term');
        $this->model = \DB::table('onboarding')->where('key','skills')->where('value','like',"%$term%")
            ->take(20)
            ->get();
        return $this->sendResponse();
    }

    public function getNetworkFollowers(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;

        // get all profile ids which is related to all job profile specilazation cuisine establishment type
        $profileIds = $this->getAllProfileIds($loggedInProfileId);
        $profileIds = $profileIds->unique();
        $length = $profileIds->count();
        $profileIds = $profileIds->random($length);

        if($profileIds->count() > 0)
            $repeatProfileIds = $profileIds->toArray();
        else
            $repeatProfileIds = [];
        $this->model = [];

        foreach ($profileIds as $key => $value)
        {
            if($loggedInProfileId == $value)
            {
                unset($profileIds[$key]);
                continue;
            }
            $profileIds[$key] = "profile:small:".$value ;
        }
        $data = [];
        if(count($profileIds)> 0)
        {
            $data = \Redis::mget($profileIds);

        }
        $profileData = [];
        if(count($data))
        {
            foreach($data as &$profile){
                if(is_null($profile)){
                    continue;
                }
                $profile = json_decode($profile);
                $profile->isFollowing = \Redis::sIsMember("followers:profile:".$profile->id,$loggedInProfileId) === 1;
                $profile->self = false;
                $profileData[] = $profile;
            }
        }
        foreach($data as &$profile){
            if(is_null($profile)){
                continue;
            }
            $profile = json_decode($profile);
            $profile->isFollowing = \Redis::sIsMember("followers:profile:".$profile->id,$loggedInProfileId) === 1;
            $profile->self = false;
            $profileData[] = $profile;
        }
        // title is header in boarding
        // type is data of item is profile or company
        // ui_type = 0  is horizontal , ui_type = 1 is vertical
        // color_code = 0 white and color_code = 1 is gray
        if(count($profileData))
<<<<<<< HEAD
            $this->model[] = ['title'=>'Your Selection','type'=>'profile','ui_type'=>0,'item'=>$profileData,'color_code'=>'rgb(255, 255, 255)'];
=======
            $this->model[] = ['title'=>'TAGTASTE RECOMMENDATIONS','type'=>'profile','ui_type'=>0,'item'=>$profileData,'color_code'=>'rgb(255, 255, 255)'];
>>>>>>> 3846fa638ce56048685dc5d2f7833076b13f5ba8

        $foundationTeamIds = [1,10,32,165,44,556,2,4,13,637,7,2245,12,6,1585,359,1467,8,1775,3379,1574,14,15,7585,1016];

        foreach ($foundationTeamIds as $key => $value)
        {
<<<<<<< HEAD
            if($loggedInProfileId == $value)
=======
            if($loggedInProfileId == $value || in_array($value,$repeatProfileIds))
>>>>>>> 3846fa638ce56048685dc5d2f7833076b13f5ba8
            {
                unset($foundationTeamIds[$key]);
                continue;
            }
            $foundationTeamIds[$key] = "profile:small:".$value ;
        }

        if(count($foundationTeamIds)> 0)
        {
            $data = \Redis::mget($foundationTeamIds);

        }
        $profileData = [];

        foreach($data as $key => &$profile){
            if(is_null($profile)){
                unset($data[$key]);
                continue;
            }
            $profile = json_decode($profile);
            $profile->isFollowing = \Redis::sIsMember("followers:profile:".$profile->id,$loggedInProfileId) === 1;
            $profile->self = false;
            $profileData[] = $profile;
        }
        if(count($profileData))
<<<<<<< HEAD
            $this->model[] = ['title'=>'Foundation team','type'=>'profile','ui_type'=>0,'item'=>$profileData,'color_code'=>'rgb(247, 247, 247)'];
        if(count($profileData))
            $this->model[] = ['title'=>'Activity Based','type'=>'profile','ui_type'=>0,'item'=>$profileData,'color_code'=>'rgb(247, 247, 247)'];
=======
            $this->model[] = ['title'=>'FOUNDING TEAM','type'=>'profile','ui_type'=>0,'item'=>$profileData,'color_code'=>'rgb(247, 247, 247)'];

        $activityBasedIds = [804,70,5555,27,685,626,2376,71,530,1315,48,961,383,1195,354,358,123,238,4338,787];

        foreach ($activityBasedIds as $key => $value)
        {
            if($loggedInProfileId == $value || in_array($value,$repeatProfileIds))
            {
                unset($activityBasedIds[$key]);
                continue;
            }
            $activityBasedIds[$key] = "profile:small:".$value ;
        }

        if(count($activityBasedIds)> 0)
        {
            $data = \Redis::mget($activityBasedIds);

        }
        $profileData = [];

        foreach($data as $key => &$profile){
            if(is_null($profile)){
                unset($data[$key]);
                continue;
            }
            $profile = json_decode($profile);
            $profile->isFollowing = \Redis::sIsMember("followers:profile:".$profile->id,$loggedInProfileId) === 1;
            $profile->self = false;
            $profileData[] = $profile;
        }

        if(count($profileData))
            $this->model[] = ['title'=>'ACTIVE & INFLUENTIAL','type'=>'profile','ui_type'=>1,'item'=>$profileData,'color_code'=>'rgb(247, 247, 247)'];
>>>>>>> 3846fa638ce56048685dc5d2f7833076b13f5ba8

//        $this->model['activity_based'] = $profileData; // should be later

        $companyIds = [111,137,322,84,11,321,277,271,253,245,204,197,193,187,186];
        $companyData = [];
        foreach($companyIds as &$companyId)
        {
            $companyId = "company:small:" . $companyId;
        }
        $data = \Redis::mget($companyIds);
        foreach($data as $key => &$company){
            if(is_null($company)){
                unset($data[$key]);
                continue;
            }
            $company = json_decode($company);
            $company->isFollowing = \Redis::sIsMember("following:profile:" . $loggedInProfileId,"company." . $company->id) === 1;
            $companyData[] = $company;
        }
//        $this->model['company'] = $companyData;
        if(count($companyData))
<<<<<<< HEAD
            $this->model[] = ['title'=>'Company Profile','type'=>'company','ui_type'=>1,'item'=>$companyData,'color_code'=>'rgb(255, 255, 255)'];
=======
            $this->model[] = ['title'=>'COMPANIES TO FOLLOW','type'=>'company','ui_type'=>0,'item'=>$companyData,'color_code'=>'rgb(255, 255, 255)'];
>>>>>>> 3846fa638ce56048685dc5d2f7833076b13f5ba8

        return $this->sendResponse();
    }

    public function getAllProfileIds($loggedInProfileId)
    {
        $profileIds = new Collection();

        // specialization
        $ids = \DB::table('profile_specializations')->where('profile_id',$loggedInProfileId)->get()->pluck('specialization_id');
        $ids = \DB::table('profile_specializations')->whereIn('specialization_id',$ids)->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);

//        // cuisine
//        $ids = \DB::table('profiles_cuisines')->where('profile_id',$loggedInProfileId)->get()->pluck('cuisine_id');
//        $ids = \DB::table('profiles_cuisines')->whereIn('cuisine_id',$ids)->get()->pluck('profile_id');
//        $profileIds = $profileIds->merge($ids);
//
//        //establishment type
//        $ids = \DB::table('profile_establishment_types')->where('profile_id',$loggedInProfileId)->get()->pluck('establishment_type_id');
//        $ids = \DB::table('profile_establishment_types')->whereIn('establishment_type_id',$ids)->get()->pluck('profile_id');
//        $profileIds = $profileIds->merge($ids);

        // job profile
        $ids = \DB::table('profile_occupations')->where('profile_id',$loggedInProfileId)->get()->pluck('occupation_id');
        $ids = \DB::table('profile_occupations')->whereIn('occupation_id',$ids)->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);

        //locations
        $profile = Profile::where('id',$loggedInProfileId)->first();
        $ids = \DB::table('profile_filters')->where('key','location')->where('value',$profile->city)->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);

        return $profileIds;
    }
}
