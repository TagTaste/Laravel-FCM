<?php

namespace App\Http\Controllers\Api\V1;

use App\Company;
use App\Education;
use App\Profile;
use App\Profile\Experience;
use App\Recipe\Collaborate;
use App\SearchClient;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Collection;

class SearchController extends Controller
{
    //aliases added for frontend
    private $models = [
        'collaborate'=> \App\Recipe\Collaborate::class,
        'collaborates'=> \App\Recipe\Collaborate::class,
        'recipe' => \App\Recipe::class,
        'recipes' => \App\Recipe::class,
        'profile' => \App\Recipe\Profile::class,
        'people' => \App\Recipe\Profile::class,
        'company' => \App\Recipe\Company::class,
        'companies' => \App\Recipe\Company::class,
        'job' => \App\Recipe\Job::class,
        'jobs' => \App\Recipe\Job::class
    ];
    
    private $filters = [
        'collaborate'=> \App\Filter\Collaborate::class,
        'recipe' => \App\Filter\Recipe::class,
        'recipes' => \App\Filter\Recipe::class,
        'profile' => \App\Filter\Profile::class,
        'people' => \App\Filter\Profile::class,
        'company' => \App\Filter\Company::class,
        'companies' => \App\Filter\Company::class,
        'job' => \App\Filter\Job::class,
        'jobs' => \App\Filter\Job::class
    ];


    public function discover(Request $request)
    {

        $loggedInProfileId = $request->user()->profile->id;
        $this->model = [];
        $profileIds = $this->getAllProfileIdsFromNetwork($loggedInProfileId);
        $profileIds = $profileIds->unique();
        $length = $profileIds->count();
        $profileIds = $profileIds->random($length);

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
                if($profile->isFollowing)
                    continue;
                $profileData[] = $profile;
            }
        }
        if(count($profileData))
            $this->model[] = ['title'=>'Networking Recommendations','subtitle'=>'FROM CHEF AND FOOD SAFETY','type'=>'profile','ui_type'=>0,'item'=>$profileData,'color_code'=>'rgb(255, 255, 255)'];

        $specializations = \DB::table('specializations')->get();

        if(count($specializations))
            $this->model[] = ['title'=>'Explore in Specializations','subtitle'=>'LENSES FOR THE F&B INDUSTRY','type'=>'profile_data','ui_type'=>0,'item'=>$specializations,'color_code'=>'rgb(255, 255, 255)'];

        $collaborations = Collaborate::where('state',1)->skip(0)->take(10)->inRandomOrder()->get();

        if(count($collaborations))
            $this->model[] = ['title'=>'Collaborate','subtitle'=>'BUSINESS OPPORTUNITIES FOR YOU ','type'=>'collaborate','ui_type'=>1,'item'=>$collaborations,'color_code'=>'rgb(255, 255, 255)'];

        $profileIds = $this->getAllProfileIdsFromExperience($loggedInProfileId);

        $profileIds = $profileIds->unique();
        $length = $profileIds->count();
        $profileIds = $profileIds->random($length);

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
                if($profile->isFollowing)
                    continue;
                $profileData[] = $profile;
            }
        }

        if(count($profileData))
            $this->model[] = ['title'=>'Your Experience','subtitle'=>null,'type'=>'profile','ui_type'=>1,'item'=>$profileData,'color_code'=>'rgb(255, 255, 255)'];

        $profileIds = $this->getAllProfileIdsFromExperience($loggedInProfileId);

        $profileIds = $profileIds->unique();
        $length = $profileIds->count();
        $profileIds = $profileIds->random($length);

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
                if($profile->isFollowing)
                    continue;
                $profileData[] = $profile;
            }
        }
        if(count($profileData))
            $this->model[] = ['title'=>'Your Education','subtitle'=>null,'type'=>'profile','ui_type'=>1,'item'=>$profileData,'color_code'=>'rgb(255, 255, 255)'];

        return $this->sendResponse();
    }


    public function getAllProfileIdsFromNetwork($loggedInProfileId)
    {
        $profileIds = new Collection();
        // specialization
        $ids = \DB::table('profile_specializations')->where('profile_id',$loggedInProfileId)->get()->pluck('specialization_id');
        $ids = \DB::table('profile_specializations')->whereIn('specialization_id',$ids)->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);

        //job profile
        $ids = \DB::table('profile_occupations')->where('profile_id',$loggedInProfileId)->get()->pluck('occupation_id');
        $ids = \DB::table('profile_occupations')->whereIn('occupation_id',$ids)->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);

        //locations
        $profile = Profile::where('id',$loggedInProfileId)->first();
        $ids = \DB::table('profile_filters')->where('key','location')->where('value',$profile->city)->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);

        //interest
        $ids = \DB::table('profiles_interested_collections')->where('profile_id',$loggedInProfileId)->get()->pluck('occupation_id');
        $ids = \DB::table('profiles_interested_collections')->whereIn('interested_collection_id',$ids)->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);

        return $profileIds;
    }

    public function getAllProfileIdsFromExperience($loggedInProfileId)
    {
        $profileIds = new Collection();
        $experiences = Experience::where('profile_id',$loggedInProfileId)->get()->pluck('company');
        $ids = \DB::table('profile_filters')->where(function ($query) use($experiences) {
                            for ($i = 0; $i < count($experiences); $i++){
                                $query->orwhere('value', 'like',  '%' . $experiences[$i] .'%');
                            }
                        })->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);
        return $profileIds;
    }

    public function getAllProfileIdsFromEducation($loggedInProfileId)
    {
        $profileIds = new Collection();
        $educations = Education::where('profile_id',$loggedInProfileId)->get()->pluck('company');
        $ids = \DB::table('profile_filters')->where(function ($query) use($educations) {
            for ($i = 0; $i < count($educations); $i++){
                $query->orwhere('value', 'like',  '%' . $educations[$i] .'%');
            }
        })->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);
        return $profileIds;
    }

    public function searchSpecializationPeople(Request $request, $id)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $profileIds = \DB::table('profile_specializations')->where('specialization_id',$id)->get()->pluck('profile_id');
        $profileIds = $profileIds->unique();
        $length = $profileIds->count();
        $profileIds = $profileIds->random($length);

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
                if($profile->isFollowing)
                    continue;
                $profileData[] = $profile;
            }
        }
        $this->model = $profileData;
        return $this->sendResponse();
    }

}
