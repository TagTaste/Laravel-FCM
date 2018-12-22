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


    private function getModels($type, $ids = [], $filters = [],$skip = null ,$take = null)
    {
        if(empty($ids)){
            return false;
        }

        $model = isset($this->models[$type]) ? new $this->models[$type] : false;
        if(!$model){
            return $model;
        }

        if(!empty($filters) && isset($this->filters[$type])){
            $modelIds = $this->filters[$type]::getModelIds($filters,$skip,$take);
            if($modelIds->count()){
                $ids = array_merge($ids,$modelIds->toArray());
            }
            return $model::whereIn('id',$ids)->whereNull('deleted_at')->get();

        }

        $model = $model::whereIn('id',$ids)->whereNull('deleted_at');

        if(null !== $skip && null !== $take){
            $model = $model->skip($skip)->take($take);
        }

        return $model->get();


    }

    //index = db
    //type = table
    //document = row
    //field = column


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
        if($length && !is_array($profileIds))
            $profileIds = $profileIds->toArray();
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
        if(count($profileData))
            $this->model[] = ['title'=>'Suggested People','subtitle'=>'','type'=>'profile','ui_type'=>0,'item'=>$profileData,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1];

        $specializations = \DB::table('specializations')->get();

        if(count($specializations))
            $this->model[] = ['title'=>'Explore in Specializations','subtitle'=>'LENSES FOR THE F&B INDUSTRY','type'=>'specializations','ui_type'=>0,'item'=>$specializations,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>0];

        $collaborations = Collaborate::where('state',1)->skip(0)->take(5)->inRandomOrder()->get();

        if(count($collaborations))
            $this->model[] = ['title'=>'Collaborate','subtitle'=>'BUSINESS OPPORTUNITIES FOR YOU ','type'=>'collaborate','ui_type'=>2,'item'=>$collaborations,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1];

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
        if($length && !is_array($profileIds))
            $profileIds = $profileIds->toArray();
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

        if(count($profileData))
            $this->model[] = ['title'=>'Your Experience','subtitle'=>null,'type'=>'profile','ui_type'=>0,'item'=>$profileData,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1];

        $profileIds = $this->getAllProfileIdsFromEducation($loggedInProfileId);

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
        if($length && !is_array($profileIds))
            $profileIds = $profileIds->toArray();
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
        if(count($profileData))
            $this->model[] = ['title'=>'Your Education','subtitle'=>null,'type'=>'profile','ui_type'=>0,'item'=>$profileData,'color_code'=>'rgb(255, 255, 255)'];

        return $this->sendResponse();
    }


    public function getAllProfileIdsFromNetwork($loggedInProfileId)
    {
        $profileIds = new Collection();
        // specialization
        $ids = \DB::table('profile_specializations')->where('profile_id',$loggedInProfileId)->take(5)->get()->pluck('specialization_id');
        $ids = \DB::table('profile_specializations')->whereIn('specialization_id',$ids)->take(5)->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);

        //job profile
        $ids = \DB::table('profile_occupations')->where('profile_id',$loggedInProfileId)->take(5)->get()->pluck('occupation_id');
        $ids = \DB::table('profile_occupations')->whereIn('occupation_id',$ids)->take(5)->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);

        //locations
        $profile = Profile::where('id',$loggedInProfileId)->first();
        $ids = \DB::table('profile_filters')->where('key','location')->where('value',$profile->city)->take(5)->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);

        //interest
        $ids = \DB::table('profiles_interested_collections')->where('profile_id',$loggedInProfileId)->take(5)->get()->pluck('occupation_id');
        $ids = \DB::table('profiles_interested_collections')->whereIn('interested_collection_id',$ids)->take(5)->get()->pluck('profile_id');
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
                        })->take(10)->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);
        return $profileIds;
    }

    public function getAllProfileIdsFromEducation($loggedInProfileId)
    {
        $profileIds = new Collection();
        $educations = Education::where('profile_id',$loggedInProfileId)->get()->pluck('college');
        $ids = \DB::table('profile_filters')->where(function ($query) use($educations) {
            for ($i = 0; $i < count($educations); $i++){
                $query->orwhere('value', 'like',  '%' . $educations[$i] .'%');
            }
        })->take(10)->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);
        return $profileIds;
    }

    public function searchSpecializationPeople(Request $request, $id)
    {
        //paginate
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $loggedInProfileId = $request->user()->profile->id;
        $profileIds = \DB::table('profile_specializations')->where('specialization_id',$id)->skip($skip)->take($take)->get()->pluck('profile_id');
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
        if($length && !is_array($profileIds))
            $profileIds = $profileIds->toArray();
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
        $this->model = $profileData;
        return $this->sendResponse();
    }

    public function search(Request $request, $type = null)
    {
        $query = $request->input('q');
        $this->model = [];
        $finalData = [];
        $params = [
            'index' => "api",
            'body' => [
                'query' => [
                    'query_string' => [
                        'query' => $query
                    ]
                ]
            ]
        ];

        $this->setType($type);

        if($type){
            $params['type'] = $type;
        }
        $client = SearchClient::get();

        $response = $client->search($params);

        if($response['hits']['total'] > 0){

            $hits = collect($response['hits']['hits']);
            $hits = $hits->groupBy("_type");

            $page = $request->input('page');
            list($skip,$take) = \App\Strategies\Paginator::paginate($page);

            foreach($hits as $name => $hit){
                $this->model[$name] = $this->getModels($name,$hit->pluck('_id')->toArray(),$request->input('filters'),$skip,$take);
            }

            $profileId = $request->user()->profile->id;
            $dataCount = 0;
            if(isset($this->model['profile']) && $this->model['profile']->count() > 0){
                $this->model['profile'] = $this->model['profile']->toArray();
                $following = \Redis::sMembers("following:profile:" . $profileId);
                foreach($this->model['profile'] as &$profile){
                    if($dataCount > 4)
                        break;
                    if($profile && isset($profile['id'])){
                        $profile['isFollowing'] = in_array($profile['id'],$following);
                        $profileData[] = $profile;
                    }
                    $dataCount++;
                }
                $finalData[] = ['type'=>'profile','ui_type'=>0,'item'=>$profileData,'count'=>count($this->model['profile'])];
            }
            $dataCount = 0;
            if(isset($this->model['company']) && $this->model['company']->count() > 0){
                $this->model['company'] = $this->model['company']->toArray();
                $companyData = [];
                foreach($this->model['company'] as $company){
                    if($dataCount > 4)
                        break;
                    $company['isFollowing'] = Company::checkFollowing($profileId,$company['id']);
                    $companyData[] = $company;
                    $dataCount++;
                }
                $finalData[] = ['type'=>'company','ui_type'=>0,'item'=>$companyData,'count'=>count($this->model['company'])];
            }
            $dataCount = 0;
            if(isset($this->model['collaborate']) && $this->model['collaborate']->count() > 0){
                $this->model['collaborate'] = $this->model['collaborate']->toArray();
                $collaborateData = [];
                foreach($this->model['collaborate'] as $collaborate){
                    if($dataCount > 4)
                        break;
                    $collaborateData[] = $collaborate;
                    $dataCount++;
                }
                $finalData[] = ['type'=>'collaborate','ui_type'=>0,'item'=>$collaborateData,'count'=>count($this->model['collaborate'])];
            }
            $this->model = $finalData;
        }


        return $this->sendResponse();
    }

    private function setType(&$type){
        //for frontend peeps
        switch($type){
            case "companies":
                $type = "company";
                break;
            case "recipes":
                $type = "recipe";
                break;
            case "people":
                $type = "profile";
                break;
            case "jobs":
                $type = "job";
                break;
        }
    }


}
