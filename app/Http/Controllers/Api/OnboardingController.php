<?php

namespace App\Http\Controllers\Api;

use App\Onboarding;
use App\Recipe\Profile;
use App\SearchClient;
use Illuminate\Database\Eloquent\Collection;
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

    public function getFollowers(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;

        // get all profile ids which is related to all job profile specilazation cuisine establishment type
        $profileIds = $this->getAllProfileIds($loggedInProfileId);
        $profileIds = $profileIds->unique();

        $profileIds = $profileIds->random(25);

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

        if(count($profileIds)> 0)
        {
            $data = \Redis::mget($profileIds);

        }
        foreach($data as &$profile){
            if(is_null($profile)){
                continue;
            }
            $profile = json_decode($profile);
            $profile->isFollowing = \Redis::sIsMember("followers:profile:".$profile->id,$loggedInProfileId) === 1;
            $profile->self = false;
        }

        $foundationTeam = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25];

        $this->model['from_selection'] = $data;

        foreach ($profileIds as $key => $value)
        {
            if($loggedInProfileId == $value)
            {
                unset($profileIds[$key]);
                continue;
            }
            $profileIds[$key] = "profile:small:".$value ;
        }

        if(count($profileIds)> 0)
        {
            $data = \Redis::mget($profileIds);

        }
        foreach($data as $key => &$profile){
            if(is_null($profile)){
                unset($data[$key]);
                continue;
            }
            $profile = json_decode($profile);
            $profile->isFollowing = \Redis::sIsMember("followers:profile:".$profile->id,$loggedInProfileId) === 1;
            $profile->self = false;
        }

        $this->model['foundation_team'] = $data;

        $this->model['activity_based'] = $data; // should be later

        $companyIds = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15];

        foreach($companyIds as &$companyId)
        {
            $companyId = "company:small:" . $companyId;
        }
        $data = \Redis::mget($companyIds);
        foreach($data as &$company){
            $company = json_decode($company);
            $company->isFollowing = \Redis::sIsMember("following:profile:" . $loggedInProfileId,"company." . $company->id) === 1;
        }
        $this->model['company'] = $data;
        return $this->sendResponse();
    }

    public function getAllProfileIds($loggedInProfileId)
    {
        $profileIds = new Collection();

        // specialization
        $ids = \DB::table('profile_specializations')->where('profile_id',$loggedInProfileId)->get()->pluck('specialization_id');
        $ids = \DB::table('profile_specializations')->whereIn('specialization_id',$ids)->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);

        // cuisine
        $ids = \DB::table('profiles_cuisines')->where('profile_id',$loggedInProfileId)->get()->pluck('cuisine_id');
        $ids = \DB::table('profiles_cuisines')->whereIn('cuisine_id',$ids)->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);

        //establishment type
        $ids = \DB::table('profile_establishment_types')->where('profile_id',$loggedInProfileId)->get()->pluck('establishment_type_id');
        $ids = \DB::table('profile_establishment_types')->whereIn('establishment_type_id',$ids)->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);

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
