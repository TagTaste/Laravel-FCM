<?php

namespace App\Http\Controllers\Api\V1;

use App\Company;
use App\Education;
use App\ProductCategory;
use App\Profile;
use App\Profile\Experience;
use App\PublicReviewProduct;
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
        'jobs' => \App\Recipe\Job::class,
        'product' => \App\PublicReviewProduct::class
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
        'jobs' => \App\Filter\Job::class,
        'product' => \App\Filter\PublicReviewProduct::class
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
        if($length)
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
            $this->model[] = ['title'=>'Suggested People','subtitle'=>'BASED ON YOUR INTERESTS','type'=>'profile','ui_type'=>0,'item'=>$profileData,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1];

        $specializations = \DB::table('specializations')->get();

        if(count($specializations))
            $this->model[] = ['title'=>'Explore by Specializations','subtitle'=>null,'type'=>'specializations','ui_type'=>0,'item'=>$specializations,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>0];

        $collaborations = Collaborate::where('state',1)->skip(0)->take(5)->inRandomOrder()->get();

        if(count($collaborations))
            $this->model[] = ['title'=>'Collaborations','subtitle'=>'BUSINESS OPPORTUNITIES FOR YOU ','type'=>'collaborate','ui_type'=>2,'item'=>$collaborations,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1];



        $companyData = \App\Recipe\Company::whereNull('deleted_at')->skip(0)->take(15)->inRandomOrder()->get();
        $data = $companyData;
        $companyData = [];
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
            $this->model[] = ['title'=>'Companies to Follow','type'=>'company','ui_type'=>0,'item'=>$companyData,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1];

        $activityBasedIds = [804,70,5555,27,685,626,2376,71,530,1315,48,961,383,1195,354,358,123,238,4338,787];

        foreach ($activityBasedIds as $key => $value)
        {
            if($loggedInProfileId == $value)
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
            $this->model[] = ['title'=>'Active & Influential','type'=>'profile','ui_type'=>1,'item'=>$profileData,'color_code'=>'rgb(255, 255, 255)'];


        $data = $this->getAllProfileIdsFromExperience($loggedInProfileId);
        $profileIds = $data['profileIds'];
        $filters = $data['filters'];
        $profileIds = $profileIds->unique();
        $length = $profileIds->count();
        if($length)
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
            $this->model[] = ['title'=>'People you might know','subtitle'=>null,'type'=>'profile','ui_type'=>0,'item'=>$profileData,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1,'filters'=>$filters];

//        $profileIds = $this->getAllProfileIdsFromEducation($loggedInProfileId);
//
//        $profileIds = $profileIds->unique();
//        $length = $profileIds->count();
//        $profileIds = $profileIds->random($length);
//
//        foreach ($profileIds as $key => $value)
//        {
//            if($loggedInProfileId == $value)
//            {
//                unset($profileIds[$key]);
//                continue;
//            }
//            $profileIds[$key] = "profile:small:".$value ;
//        }
//        if($length && !is_array($profileIds))
//            $profileIds = $profileIds->toArray();
//        $data = [];
//        if(count($profileIds)> 0)
//        {
//            $data = \Redis::mget($profileIds);
//
//        }
//        $profileData = [];
//        if(count($data))
//        {
//            foreach($data as &$profile){
//                if(is_null($profile)){
//                    continue;
//                }
//                $profile = json_decode($profile);
//                $profile->isFollowing = \Redis::sIsMember("followers:profile:".$profile->id,$loggedInProfileId) === 1;
//                $profile->self = false;
//                $profileData[] = $profile;
//            }
//        }
//        if(count($profileData))
//            $this->model[] = ['title'=>'Your Education','subtitle'=>null,'type'=>'profile','ui_type'=>0,'item'=>$profileData,'color_code'=>'rgb(255, 255, 255)'];

        return $this->sendResponse();
    }


    public function getAllProfileIdsFromNetwork($loggedInProfileId)
    {
        $profileIds = new Collection();
        // specialization
        $ids = \DB::table('profile_specializations')->where('profile_id',$loggedInProfileId)->take(5)->inRandomOrder()->get()->pluck('specialization_id');
        $ids = \DB::table('profile_specializations')->whereIn('specialization_id',$ids)->take(5)->inRandomOrder()->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);

        //job profile
        $ids = \DB::table('profile_occupations')->where('profile_id',$loggedInProfileId)->take(5)->inRandomOrder()->get()->pluck('occupation_id');
        $ids = \DB::table('profile_occupations')->whereIn('occupation_id',$ids)->take(5)->inRandomOrder()->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);

        //locations
        $profile = Profile::where('id',$loggedInProfileId)->first();
        $ids = \DB::table('profile_filters')->where('key','location')->where('value',$profile->city)->take(5)->inRandomOrder()->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);

        //interest
        $ids = \DB::table('profiles_interested_collections')->where('profile_id',$loggedInProfileId)->take(5)->inRandomOrder()->get()->pluck('occupation_id');
        $ids = \DB::table('profiles_interested_collections')->whereIn('interested_collection_id',$ids)->take(5)->inRandomOrder()->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);

        return $profileIds;
    }

    public function getAllProfileIdsFromExperience($loggedInProfileId)
    {
        $profileIds = new Collection();
        $experiences = Experience::where('profile_id',$loggedInProfileId)->get()->pluck('company');
        $filters = [];
        $filters[]= ['key'=>'experience','value'=>array_unique($experiences->toArray())];

        $ids = \DB::table('profile_filters')->where(function ($query) use($experiences) {
                            for ($i = 0; $i < count($experiences); $i++){
                                $query->orwhere('value', 'like',  '%' . $experiences[$i] .'%');
                            }
                        })->take(10)->inRandomOrder()->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);
        $educations = Education::where('profile_id',$loggedInProfileId)->get()->pluck('college');
        $filters[]= ['key'=>'education','value'=>array_unique($educations->toArray())];
        $ids = \DB::table('profile_filters')->where(function ($query) use($educations) {
            for ($i = 0; $i < count($educations); $i++){
                $query->orwhere('value', 'like',  '%' . $educations[$i] .'%');
            }
        })->take(10)->inRandomOrder()->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);

        return ['profileIds'=>$profileIds,'filters'=>$filters];
    }

    public function getAllProfileIdsFromEducation($loggedInProfileId)
    {
        $profileIds = new Collection();
        $educations = Education::where('profile_id',$loggedInProfileId)->get()->pluck('college');
        $ids = \DB::table('profile_filters')->where(function ($query) use($educations) {
            for ($i = 0; $i < count($educations); $i++){
                $query->orwhere('value', 'like',  '%' . $educations[$i] .'%');
            }
        })->take(10)->inRandomOrder()->get()->pluck('profile_id');
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
        if($length)
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
                $searched = $this->getModels($name,$hit->pluck('_id')->toArray(),$request->input('filters'),$skip,$take);
                $suggestions = $this->filterSuggestions($query,$name,$skip,$take);
                $suggested = collect([]);
                if(!empty($suggestions)){
                    $suggested = $this->getModels($name,array_pluck($suggestions,'id'));
                }

                $this->model[$name] = $searched->merge($suggested)->sortBy('name');
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
                $finalData[] = ['title'=>'People','type'=>'profile','ui_type'=>2,'item'=>$profileData,'count'=>count($this->model['profile'])];
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
                $finalData[] = ['title'=>'Companies','type'=>'company','ui_type'=>2,'item'=>$companyData,'count'=>count($this->model['company'])];
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
                $finalData[] = ['title'=>'Collaborations','type'=>'collaborate','ui_type'=>2,'item'=>$collaborateData,'count'=>count($this->model['collaborate'])];
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

    private function filterSuggestions(&$term,$type = null,$skip,$take)
    {
        $suggestions = [];

        if(null === $type || "profile" === $type){
            $profiles = \DB::table("profiles")->select("profiles.id")
                ->join("users",'users.id','=','profiles.user_id')
                ->where("users.name",'like',"%$term%")
                ->whereNull('users.deleted_at')
                ->skip($skip)
                ->take($take)
                ->get();

            if(count($profiles)){
                foreach($profiles as $profile){
                    $profile->type = "profile";
                    $suggestions[] = (array) $profile;
                }
            }

        }

        if(null === $type || "company" === $type){
            $companies = \DB::table("companies")->whereNull('companies.deleted_at')
                ->select("companies.id")
                ->join("profiles",'companies.user_id','=','profiles.user_id')
                ->where("name",'like',"%$term%")
                ->whereNull('profiles.deleted_at')
                ->whereNull('companies.deleted_at')
                ->skip($skip)
                ->take($take)
                ->get();

            if(count($companies)){
                foreach($companies as $company){
                    $company->type = "company";
                    $suggestions[] = (array) $company;
                }
            }
        }


        return $suggestions;
    }

    public function explore(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $model = [];
        // ui type data
        // 0 - horizontal with crousal
        // 1 - vertical with crousal
        // 2 - vertical without crousal
        // 3 single item data
        // 4
        $chefOfTheWeekProfileId = 664;
        $chefOfTheWeekProfile = \Redis::get('profile:small:' . $chefOfTheWeekProfileId);
        $data = json_decode($chefOfTheWeekProfile);
        $data->isFollowing = \Redis::sIsMember("followers:profile:".$data->id,$loggedInProfileId) === 1;
        $model[] = ['title'=>"Chef of the Week", "subtitle"=>null,"description"=>"Maecenas faucibus mollis interdum. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. 
                  Maecenas sed diam eget risus varius blandit sit amet non magna.Maecenas sed diam eget risus varius.", "type"=>"profile","item"=>$data,"ui_type"=>4,"color_code"=>"rgb(255, 255, 255)","is_see_more"=>0];


        $profileIds = $this->getAllProfileIdsFromNetwork($loggedInProfileId);
        $profileIds = $profileIds->unique();
        $length = $profileIds->count();
        if($length)
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
            $model[] = ['title'=>'Suggested People','subtitle'=>'BASED ON YOUR INTERESTS','type'=>'profile','ui_type'=>0,'item'=>$profileData,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1];

        $activityBasedIds = [804,70,5555,27,685,626,2376,71,530,1315,48,961,383,1195,354,358,123,238,4338,787];

        foreach ($activityBasedIds as $key => $value)
        {
            if($loggedInProfileId == $value)
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
            $model[] = ['title'=>'ACTIVE & INFLUENTIAL','subtitle'=>null,'type'=>'profile','ui_type'=>1,'item'=>$profileData,'color_code'=>'rgb(247, 247, 247)','is_see_more'=>1];


        $weekOfTheCompanyId = 55;
        $weekOfTheCompany = \Redis::get('company:small:' . $weekOfTheCompanyId);
        $data = json_decode($weekOfTheCompany);
        if(!is_null($data))
        {
            $data->isFollowing = \Redis::sIsMember("followers:profile:".$data->id,$loggedInProfileId) === 1;
            $model[] = ['title'=>"Company in Focus", "subtitle"=>null,"description"=>"Maecenas faucibus mollis interdum. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. 
                  Maecenas sed diam eget risus varius blandit sit amet non magna.Maecenas sed diam eget risus varius.", "type"=>"company","item"=>$data,"ui_type"=>4,"color_code"=>"rgb(255, 255, 255)","is_see_more"=>0];
        }

        $companyData = \App\Recipe\Company::whereNull('deleted_at')->skip(0)->take(15)->inRandomOrder()->get();
        $data = $companyData;
        $companyData = [];
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
            $model[] = ['title'=>'Companies to Follow','type'=>'company','ui_type'=>0,'item'=>$companyData,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1];

        if(count($companyData))
            $model[] = ['title'=>'More Companies to Follow','type'=>'company','ui_type'=>1,'item'=>$companyData,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1];


        $collaborations = Collaborate::where('state',1)->where('collaborate_type','!=','product-review')->skip(0)->take(5)->inRandomOrder()->get();

        if(count($collaborations))
            $model[] = ['title'=>'Collaborations','subtitle'=>'BUSINESS OPPORTUNITIES FOR YOU ','type'=>'collaborate','ui_type'=>2,'item'=>$collaborations,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1];


        $collaborations = Collaborate::where('state',1)->where('collaborate_type','=','product-review')->skip(0)->take(5)->inRandomOrder()->get();

        if(count($collaborations))
            $model[] = ['title'=>'Collaborations','subtitle'=>'Product Review ','type'=>'private - collaborate','ui_type'=>2,'item'=>$collaborations,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1];


        $recommended = PublicReviewProduct::where('mark_featured',1)->inRandomOrder()->limit(10)->get();
        if($recommended->count())
            $model[] = ['title'=>'Featured Products','subtitle'=>'Products in focus this week','item'=>$recommended,
                'ui_type'=>3,'color_code'=>'rgb(255, 255, 255)','type'=>'product','is_see_more'=>1];

        //        $categories = ProductCategory::where('is_active')->get();
        $model[] = ['title'=>'Based on your Interest','subtitle'=>'DARK CHOCOLATE, WINE AND 2 OTHERS','item'=>$recommended,
            'ui_type'=>0,'color_code'=>'rgb(255, 255, 255)','type'=>'product','is_see_more'=>1];

        $recently = PublicReviewProduct::where('mark_featured',1)->orderBy('updated_at','desc')->limit(10)->get();
        if($recently->count())
            $model[] = ['title'=>'Newly Added Products','subtitle'=>'BE THE FIRST ONE TO REVIEW','item'=>$recently,
                'ui_type'=>2,'color_code'=>'rgb(255, 255, 255)','type'=>'product','is_see_more'=>1];

        $weekOfTheCategory = ["Name"=>"Ice Cream","type"=>"category","description"=>null,"image"=>"https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/share-collaboration-big.png"];
        $model[] = ['title'=>"Category of Week", "subtitle"=>null,"description"=>"Maecenas faucibus mollis interdum. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. 
                  Maecenas sed diam eget risus varius blandit sit amet non magna.Maecenas sed diam eget risus varius.", "type"=>"company","item"=>$weekOfTheCategory,"ui_type"=>4,"color_code"=>"rgb(255, 255, 255)","is_see_more"=>0];


        $categories = ProductCategory::where('is_active',1)->inRandomOrder()->limit(10)->get();
        if($categories->count())
            $model[] = ['title'=>'Categories','subtitle'=>'LENSES FOR THE F&B INDUSTRY','item'=>$categories,
                'ui_type'=>0,'color_code'=>'rgb(255, 255, 255)','type'=>'category','is_see_more'=>1];

        $specializations = \DB::table('specializations')->get();

        if(count($specializations))
            $model[] = ['title'=>'Explore by Specializations','subtitle'=>null,'type'=>'specializations','ui_type'=>0,'item'=>$specializations,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>0];

        $fbFriends = \DB::table('social_accounts')->where('user_id',$request->user()->id)->first();
        $profileIds = explode(",",$fbFriends);

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
            $model[] = ['title'=>'Facebook Friend','subtitle'=>null,'type'=>'profile','ui_type'=>0,'item'=>$profileData,'color_code'=>'rgb(247, 247, 247)','is_see_more'=>1];


        $this->model = $model;

        return $this->sendResponse();
    }
}
