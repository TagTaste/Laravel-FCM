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

        $specializations = \DB::table('specializations')->orderBy("order","ASC")->get();

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
        $experiencesData = [];
        $experiences = Experience::where('profile_id',$loggedInProfileId)->get()->pluck('company');
        foreach ($experiences as $experience)
        {
            if(!array_key_exists($experience, $experiencesData))
                $experiencesData[] = $experience;
        }
        $filters = [];
        $filters[]= ['key'=>'experience','value'=>$experiencesData];

        $ids = \DB::table('profile_filters')->where(function ($query) use($experiences) {
                            for ($i = 0; $i < count($experiences); $i++){
                                $query->orwhere('value', 'like',  '%' . $experiences[$i] .'%');
                            }
                        })->take(10)->inRandomOrder()->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);
        $educations = Education::where('profile_id',$loggedInProfileId)->get()->pluck('college');
        $educationsData = [];
        foreach ($educations as $education)
        {
            if(!array_key_exists($education, $educationsData))
                $educationsData[] = $education;
        }
        $filters[]= ['key'=>'education','value'=>$educationsData];
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

    public function exploreForSearch(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $model = [];

        /* ui type = 1 is start */

        $chefOfTheWeekProfileId = 44;
        $chefOfTheWeekProfile = \Redis::get('profile:small:' . $chefOfTheWeekProfileId);
        $data = json_decode($chefOfTheWeekProfile);
        if(!is_null($data))
        {
            $data->isFollowing = \Redis::sIsMember("followers:profile:".$data->id,$loggedInProfileId) === 1;
            $item = [$data];
            $model[] = ['title'=>"Chef of the week", "subtitle"=>null,"description"=>"Bill Marchetti started working at the tender age of 13 in a small family hotel in San Benedetto del Tronto on Italy’s Adriatic coast, he later on migrated to Australia and made his name as Specialist Italian Chef. He is a recipient of \"Insegna del Ristorante Italiano\", an international award given by the Italian Government to recognize true and authentic Italian Restaurants worldwide. Bill is known in India for his exemplary work at ITC Hotels and Spaghetti Kitchen; he is currently the culinary lead at Farm Land, one of India’s trendsetting charcuteries. Bill’s knowledge of meat is par excellence and he will soon be hosting classes for young chefs and students.", "type"=>"profile","item"=>$item,"ui_type"=>1,"color_code"=>"rgb(255, 255, 255)","is_see_more"=>0];

        }

        /* ui type = 1 is end */


        /* ui type = 2 is start */

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
            $model[] = ['title'=>'Recommendations','subtitle'=>'Based on your background & interests','type'=>'profile','ui_type'=>2,'item'=>$profileData,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1];

        /* ui type = 2 is end */


        /* ui type = 3 is start */


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
            $model[] = ['title'=>'Active & Influential','subtitle'=>null,'type'=>'profile','ui_type'=>3,'item'=>$profileData,'color_code'=>'rgb(247, 247, 247)','is_see_more'=>1];

        /* ui type = 3 is end */


        /* ui type = 4 is start */

//        $weekOfTheCompanyId = 55;
//        $weekOfTheCompany = \Redis::get('company:small:' . $weekOfTheCompanyId);
//        $data = json_decode($weekOfTheCompany);
//        if(!is_null($data))
//        {
//            $data->isFollowing = \Redis::sIsMember("followers:profile:".$data->id,$loggedInProfileId) === 1;
//            $data = [$data];
//            $model[] = ['title'=>"Company in Focus", "subtitle"=>null,"description"=>"Maecenas faucibus mollis interdum. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
//                  Maecenas sed diam eget risus varius blandit sit amet non magna.Maecenas sed diam eget risus varius.", "type"=>"company","item"=>$data,"ui_type"=>4,"color_code"=>"rgb(255, 255, 255)","is_see_more"=>0];
//        }

        /* ui type = 4 is end */



        /* ui type = 5 is end */

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
        if(count($companyData))
            $model[] = ['title'=>'Companies to follow','type'=>'company','ui_type'=>5,'item'=>$companyData,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1];

        /* ui type = 5 is end */


        /* ui type = 6 is start */


//        if(count($companyData))
//            $model[] = ['title'=>'More Companies to Follow','type'=>'company','ui_type'=>6,'item'=>$companyData,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1];


        /* ui type = 6 is end */



        /* ui type = 7 is start */

        $collaborations = Collaborate::where('state',1)->where('collaborate_type','!=','product-review')->skip(0)->take(5)->inRandomOrder()->get();

        if(count($collaborations))
            $model[] = ['title'=>'Collaborations','subtitle'=>'Interesting opportunities for you','type'=>'collaborate','ui_type'=>7,'item'=>$collaborations,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1];


        /* ui type = 7 is end */


        /* ui type = 8 is start */


//        $collaborations = Collaborate::where('state',1)->where('collaborate_type','=','product-review')->skip(0)->take(5)->inRandomOrder()->get();
//
//        if(count($collaborations))
//            $model[] = ['title'=>'Collaborations','subtitle'=>'Product Review ','type'=>'collaborate','ui_type'=>8,'item'=>$collaborations,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1];


        /* ui type = 8 is end */



        /* ui type = 9 is start */


//        $products = PublicReviewProduct::where('mark_featured',1)->inRandomOrder()->limit(10)->get();
//        $recommended = [];
//        foreach($products as $product){
//            $meta = $product->getMetaFor($loggedInProfileId);
//            $recommended[] = ['product'=>$product,'meta'=>$meta];
//        }
//        if(count($recommended))
//            $model[] = ['title'=>'Featured Products','subtitle'=>'Products in focus this week','item'=>$recommended,
//                'ui_type'=>9,'color_code'=>'rgb(255, 255, 255)','type'=>'product','is_see_more'=>1];
//


        /* ui type = 9 is end */



        /* ui type = 10 is start */

//        //        $categories = ProductCategory::where('is_active')->get();
//        $model[] = ['title'=>'Based on your Interest','subtitle'=>'DARK CHOCOLATE, WINE AND 2 OTHERS','item'=>$recommended,
//            'ui_type'=>10,'color_code'=>'rgb(255, 255, 255)','type'=>'product','is_see_more'=>1];
//


        /* ui type = 10 is end */



        /* ui type = 11 is start */

//        $products = PublicReviewProduct::where('mark_featured',1)->orderBy('updated_at','desc')->limit(10)->get();
//        $recently = [];
//        foreach($products as $product){
//            $meta = $product->getMetaFor($loggedInProfileId);
//            $recently[] = ['product'=>$product,'meta'=>$meta];
//        }
//        if(count ($recently) != 0)
//            $model[] = ['title'=>'Newly Added Products','subtitle'=>'BE THE FIRST ONE TO REVIEW','item'=>$recently,
//                'ui_type'=>11,'color_code'=>'rgb(255, 255, 255)','type'=>'product','is_see_more'=>1];
//

        /* ui type = 11 is end */






        /* ui type = 12 is start */

//        $weekOfTheCategory = [];
//        $weekOfTheCategory[] = ["Name"=>"Ice Cream","type"=>"category","description"=>null,"image"=>"https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/share-collaboration-big.png"];
//        $model[] = ['title'=>"Category of Week", "subtitle"=>null,"description"=>"Maecenas faucibus mollis interdum. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
//                  Maecenas sed diam eget risus varius blandit sit amet non magna.Maecenas sed diam eget risus varius.", "type"=>"category","item"=>$weekOfTheCategory,"ui_type"=>12,"color_code"=>"rgb(255, 255, 255)","is_see_more"=>0];
//
//
        /* ui type = 12 is end */





        /* ui type = 13 is start */

//        $categories = PublicReviewProduct\ProductCategory::where('is_active',1)->inRandomOrder()->limit(10)->get();
//        if($categories->count())
//            $model[] = ['title'=>'Categories','subtitle'=>'LENSES FOR THE F&B INDUSTRY','item'=>$categories,
//                'ui_type'=>13,'color_code'=>'rgb(255, 255, 255)','type'=>'category','is_see_more'=>1];
//


        /* ui type = 13 is end */




        /* ui type = 14 is start */

//        $recommended = PublicReviewProduct\ProductCategory::where('is_active',1)->inRandomOrder()->limit(6)->get();
//        if($recommended->count())
//            $model[] = ['title'=>'Featured Category','subtitle'=>'Products in focus this week','item'=>$recommended,
//                'ui_type'=>14,'color_code'=>'rgb(255, 255, 255)','type'=>'category','is_see_more'=>1];


        /* ui type = 14 is end */





        /* ui type = 15 is start */


        $specializations = \DB::table('specializations')->orderBy("order","ASC")->get();

        if(count($specializations))
            $model[] = ['title'=>'Explore by Specialization','subtitle'=>null,'type'=>'specializations','ui_type'=>15,'item'=>$specializations,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>0];



        /* ui type = 15 is end */




        /* ui type = 16 is start */


//        if(count($profileData))
//            $model[] = ['title'=>'See your facebook friend','subtitle'=>null,'type'=>'facebook','ui_type'=>16,'item'=>[],'color_code'=>'rgb(247, 247, 247)','is_see_more'=>0];


        /* ui type = 16 is end */


        $this->model = $model;

        return $this->sendResponse();
    }

    public function exploreForReview(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $model = [];



        /* ui type = 12 is start */

//        $weekOfTheCategory = [18];
//        $item = [];
//        $item[] = ['id'=>18,"name"=>"Confectionery","is_active"=>1,"description"=>null,"image"=>"https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/weekelyImage/category_of_week.jpg"];
//        $model[] = ['title'=>"Category of week", "subtitle"=>null,"description"=>null,
//            "type"=>"category","item"=>$item,"ui_type"=>12,"color_code"=>"rgb(255, 255, 255)","is_see_more"=>0];
//

        /* ui type = 12 is end */


        /* ui type = 1 is start */

//        $chefOfTheWeekProfileId = 7;
//        $chefOfTheWeekProfile = \Redis::get('profile:small:' . $chefOfTheWeekProfileId);
//        $data = json_decode($chefOfTheWeekProfile);
//        $data->isFollowing = \Redis::sIsMember("followers:profile:".$data->id,$loggedInProfileId) === 1;
//        $item = [$data];
//        $model[] = ['title'=>"Chef of the Week", "subtitle"=>null,"description"=>"Maecenas faucibus mollis interdum. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
//                  Maecenas sed diam eget risus varius blandit sit amet non magna.Maecenas sed diam eget risus varius.", "type"=>"profile","item"=>$item,"ui_type"=>1,"color_code"=>"rgb(255, 255, 255)","is_see_more"=>0];


        /* ui type = 1 is end */


        /* ui type = 2 is start */
//
//        $profileIds = $this->getAllProfileIdsFromNetwork($loggedInProfileId);
//        $profileIds = $profileIds->unique();
//        $length = $profileIds->count();
//        if($length)
//            $profileIds = $profileIds->random($length);
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
//            $model[] = ['title'=>'Recommendations','subtitle'=>'Based on your background & interests','type'=>'profile','ui_type'=>2,'item'=>$profileData,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1];

        /* ui type = 2 is end */


        /* ui type = 3 is start */

//
//        $activityBasedIds = [804,70,5555,27,685,626,2376,71,530,1315,48,961,383,1195,354,358,123,238,4338,787];
//
//        foreach ($activityBasedIds as $key => $value)
//        {
//            if($loggedInProfileId == $value)
//            {
//                unset($activityBasedIds[$key]);
//                continue;
//            }
//            $activityBasedIds[$key] = "profile:small:".$value ;
//        }
//
//        if(count($activityBasedIds)> 0)
//        {
//            $data = \Redis::mget($activityBasedIds);
//
//        }
//        $profileData = [];
//
//        foreach($data as $key => &$profile){
//            if(is_null($profile)){
//                unset($data[$key]);
//                continue;
//            }
//            $profile = json_decode($profile);
//            $profile->isFollowing = \Redis::sIsMember("followers:profile:".$profile->id,$loggedInProfileId) === 1;
//            $profile->self = false;
//            $profileData[] = $profile;
//        }
//
//        if(count($profileData))
//            $model[] = ['title'=>'Active & Influential','subtitle'=>null,'type'=>'profile','ui_type'=>3,'item'=>$profileData,'color_code'=>'rgb(247, 247, 247)','is_see_more'=>1];


        /* ui type = 3 is end */


        /* ui type = 4 is start */


//        $weekOfTheCompanyId = 55;
//        $weekOfTheCompany = \Redis::get('company:small:' . $weekOfTheCompanyId);
//        $data = json_decode($weekOfTheCompany);
//        if(!is_null($data))
//        {
//            $data->isFollowing = \Redis::sIsMember("followers:profile:".$data->id,$loggedInProfileId) === 1;
//            $data = [$data];
//            $model[] = ['title'=>"Company in Focus", "subtitle"=>null,"description"=>"Maecenas faucibus mollis interdum. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
//                  Maecenas sed diam eget risus varius blandit sit amet non magna.Maecenas sed diam eget risus varius.", "type"=>"company","item"=>$data,"ui_type"=>4,"color_code"=>"rgb(255, 255, 255)","is_see_more"=>0];
//        }

        /* ui type = 4 is end */



        /* ui type = 5 is end */


//        $companyData = \App\Recipe\Company::whereNull('deleted_at')->skip(0)->take(15)->inRandomOrder()->get();
//        $data = $companyData;
//        $companyData = [];
//        foreach($data as $key => &$company){
//            if(is_null($company)){
//                unset($data[$key]);
//                continue;
//            }
//            $company = json_decode($company);
//            $company->isFollowing = \Redis::sIsMember("following:profile:" . $loggedInProfileId,"company." . $company->id) === 1;
//            $companyData[] = $company;
//        }
//        if(count($companyData))
//            $model[] = ['title'=>'Companies to Follow','type'=>'company','ui_type'=>5,'item'=>$companyData,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1];


        /* ui type = 5 is end */


        /* ui type = 6 is start */


//        if(count($companyData))
//            $model[] = ['title'=>'More Companies to Follow','type'=>'company','ui_type'=>6,'item'=>$companyData,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1];


        /* ui type = 6 is end */



        /* ui type = 7 is start */

//        $collaborations = Collaborate::where('state',1)->where('collaborate_type','!=','product-review')->skip(0)->take(5)->inRandomOrder()->get();
//
//        if(count($collaborations))
//            $model[] = ['title'=>'Collaborations','subtitle'=>'Interesting opportunities for you','type'=>'collaborate','ui_type'=>7,'item'=>$collaborations,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1];


        /* ui type = 7 is end */





        /* ui type = 9 is start */


        $products = PublicReviewProduct::where('mark_featured',1)->where('is_active',1)->whereNull('deleted_at')->inRandomOrder()->limit(10)->get();
        $recommended = [];
        foreach($products as $product){
            $meta = $product->getMetaFor($loggedInProfileId);
            $recommended[] = ['product'=>$product,'meta'=>$meta];
        }
        if(count($recommended))
            $model[] = ['title'=>'Featured Products','subtitle'=>'Products in focus this week','item'=>$recommended,
                'ui_type'=>9,'color_code'=>'rgb(255, 255, 255)','type'=>'product','is_see_more'=>1];



        /* ui type = 9 is end */




        /* ui type = 8 is start */


        $collaborations = Collaborate::where('state',1)->where('collaborate_type','=','product-review')->skip(0)->take(5)->inRandomOrder()->get();

        if(count($collaborations))
            $model[] = ['title'=>'Collaborations - Product Reviews','subtitle'=>'Sensoral Reviews sponsored by companies','type'=>'collaborate','ui_type'=>8,'item'=>$collaborations,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1];


        /* ui type = 8 is end */


        /* ui type = 10 is start */

        $products = PublicReviewProduct::where('is_active',1)->whereNull('deleted_at')->inRandomOrder()->limit(10)->get();
        $recommended = [];
        foreach($products as $product){
            $meta = $product->getMetaFor($loggedInProfileId);
            $recommended[] = ['product'=>$product,'meta'=>$meta];
        }
        if(count($recommended))
            $model[] = ['title'=>'Products you\'d like to review','subtitle'=>'Based on your interests','item'=>$recommended,
            'ui_type'=>10,'color_code'=>'rgb(255, 255, 255)','type'=>'product','is_see_more'=>1];



        /* ui type = 10 is end */






        /* ui type = 13 is start */
        $categoryIds = PublicReviewProduct::with([])->where('is_active',1)->whereNull('deleted_at')->get()->pluck('product_category_id');
        $categories = PublicReviewProduct\ProductCategory::whereIn('id',$categoryIds)->where('is_active',1)->inRandomOrder()->get();
        if($categories->count())
            $model[] = ['title'=>'Explore by Category','subtitle'=>null,'item'=>$categories,
                'ui_type'=>13,'color_code'=>'rgb(255, 255, 255)','type'=>'category','is_see_more'=>0];



        /* ui type = 13 is end */





        /* ui type = 11 is start */

        $products = PublicReviewProduct::where('is_active',1)->whereNull('deleted_at')->orderBy('updated_at','desc')->limit(10)->get();
        $recently = [];
        foreach($products as $product){
            $meta = $product->getMetaFor($loggedInProfileId);
            $recently[] = ['product'=>$product,'meta'=>$meta];
        }
        if(count ($recently) != 0)
            $model[] = ['title'=>'Newly Added','subtitle'=>'Be the first one to review','item'=>$recently,
                'ui_type'=>11,'color_code'=>'rgb(255, 255, 255)','type'=>'product','is_see_more'=>1];


        /* ui type = 11 is end */




        /* ui type = 14 is start */
//        $categoryIds = PublicReviewProduct::with([])->where('is_active',1)->whereNull('deleted_at')->get()->pluck('product_category_id');
//        $recommended = PublicReviewProduct\ProductCategory::whereIn('id',$categoryIds)->where('is_active',1)->inRandomOrder()->limit(6)->get();
//        if($recommended->count())
//            $model[] = ['title'=>'Featured Category','subtitle'=>'Products in focus this week','item'=>$recommended,
//                'ui_type'=>14,'color_code'=>'rgb(255, 255, 255)','type'=>'category','is_see_more'=>0];
//

        /* ui type = 14 is end */





        /* ui type = 15 is start */


//        $specializations = \DB::table('specializations')->get();
//
//        if(count($specializations))
//            $model[] = ['title'=>'Explore by Specialization','subtitle'=>null,'type'=>'specializations','ui_type'=>15,'item'=>$specializations,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>0];
//
//

        /* ui type = 15 is end */




        /* ui type = 16 is start */


//
//        if(count($profileData))
//            $model[] = ['title'=>'Facebook Friend','subtitle'=>null,'type'=>'profile','ui_type'=>16,'item'=>$profileData,'color_code'=>'rgb(247, 247, 247)','is_see_more'=>1];
//

        /* ui type = 16 is end */


        $this->model = $model;

        return $this->sendResponse();
    }



    //        $fbFriends = \DB::table('social_accounts')->where('user_id',$request->user()->id)->first();
//        if(isset($fbFriends->fb_friends) )
//            $profileIds = explode(",",$fbFriends->fb_friends);
//        else
//            $profileIds = [];
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

        public function newExplore(Request $request)
        {
            $loggedInProfileId = $request->user()->profile->id;
            $model = [];

            /* ui type = 1 is start */

        $chefOfTheWeekProfileData = \DB::table('constant_variable_model')->where('ui_type',1)->where('model_name','profile')->where('is_active',1)->first();
        if(!is_null($chefOfTheWeekProfileData))
        {
            $chefOfTheWeekProfileData->data_json = json_decode($chefOfTheWeekProfileData->data_json);
            $chefOfTheWeekProfileId = isset($chefOfTheWeekProfileData->model_id)? (int)$chefOfTheWeekProfileData->model_id : null;
            $chefOfTheWeekProfile = Redis::get('profile:small:' . $chefOfTheWeekProfileId);
            $data = json_decode($chefOfTheWeekProfile);
            if(!is_null($data))
            {
                $data->image = isset($chefOfTheWeekProfileData->data_json->image) ? $chefOfTheWeekProfileData->data_json->image : $data->image;
                $data->image_meta = isset($chefOfTheWeekProfileData->data_json->image_meta) ? json_encode($chefOfTheWeekProfileData->data_json->image_meta,true) : $data->image_meta;
                $data->isFollowing = Redis::sIsMember("followers:profile:".$data->id,$loggedInProfileId) === 1;
                $item = [$data];
                $title = isset($chefOfTheWeekProfileData->data_json->title) ? $chefOfTheWeekProfileData->data_json->title : "Chef of the week" ;
                $subtitle = isset($chefOfTheWeekProfileData->data_json->subtitle) ? $chefOfTheWeekProfileData->data_json->subtitle : null ;
                $description = isset($chefOfTheWeekProfileData->data_json->description) ? $chefOfTheWeekProfileData->data_json->description : null ;

                $model[] = ['title'=>$title, "subtitle"=>$subtitle,"description"=>$description, "type"=>"profile","item"=>$item,"ui_type"=>1,"color_code"=>"rgb(255, 255, 255)","is_see_more"=>0];

            }
        }

            /* ui type = 1 is end */


            /* ui type = 2 is start */
            $recommendationProfileData = \DB::table('constant_variable_model')->where('ui_type',2)->where('model_name','profile')->where('is_active',1)->first();
            if(!is_null($recommendationProfileData))
            {
                $recommendationProfileData->data_json = json_decode($recommendationProfileData->data_json);

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
                    $data = Redis::mget($profileIds);

                }
                $profileData = [];
                if(count($data))
                {
                    foreach($data as &$profile){
                        if(is_null($profile)){
                            continue;
                        }
                        $profile = json_decode($profile);
                        $profile->isFollowing = Redis::sIsMember("followers:profile:".$profile->id,$loggedInProfileId) === 1;
                        $profile->self = false;
                        $profileData[] = $profile;
                    }
                }
                $title = isset($recommendationProfileData->data_json->title) ? $recommendationProfileData->data_json->title : "Recommendations";
                $subtitle = isset($recommendationProfileData->data_json->subtitle) ? $recommendationProfileData->data_json->subtitle : null ;
                if(count($profileData))
                    $model[] = ['title'=>$title,'subtitle'=>$subtitle,'type'=>'profile','ui_type'=>2,'item'=>$profileData,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1];
            }

            /* ui type = 2 is end */



            /* ui type = 3 is start */

            $activityyBasedProfileData = \DB::table('constant_variable_model')->where('ui_type',3)->where('model_name','profile')->where('is_active',1)->first();
            if(!is_null($activityyBasedProfileData))
            {
                $activityyBasedProfileData->data_json = json_decode($activityyBasedProfileData->data_json);
                $activityyBasedProfileId = explode(',',$activityyBasedProfileData);
                foreach ($activityyBasedProfileId as $key => $value)
                {
                    if($loggedInProfileId == $value)
                    {
                        unset($activityyBasedProfileId[$key]);
                        continue;
                    }
                    $activityyBasedProfileId[$key] = "profile:small:".$value ;
                }

                if(count($activityyBasedProfileId)> 0)
                {
                    $data = Redis::mget($activityyBasedProfileId);

                }
                $profileData = [];

                foreach($data as $key => &$profile){
                    if(is_null($profile)){
                        unset($data[$key]);
                        continue;
                    }
                    $profile = json_decode($profile);
                    $profile->isFollowing = Redis::sIsMember("followers:profile:".$profile->id,$loggedInProfileId) === 1;
                    $profile->self = false;
                    $profileData[] = $profile;
                }
                $title = isset($chefOfTheWeekProfileData->data_json->title) ? $chefOfTheWeekProfileData->data_json->title : 'Active & Influential' ;

                if(count($profileData))
                    $model[] = ['title'=>$title,'subtitle'=>null,'type'=>'profile','ui_type'=>3,'item'=>$profileData,'color_code'=>'rgb(247, 247, 247)','is_see_more'=>1];


            }
            /* ui type = 3 is end */


            /* ui type = 4 is start */

        $chefOfTheWeekCompanyData = \DB::table('constant_variable_model')->where('ui_type',4)->where('model_name','company')->where('is_active',1)->first();
        if(!is_null($chefOfTheWeekCompanyData))
        {
            $chefOfTheWeekCompanyData->data_json = json_decode($chefOfTheWeekCompanyData->data_json);
            $weekOfTheCompanyId = isset($chefOfTheWeekCompanyData->model_id) ? (int)$chefOfTheWeekCompanyData->model_id : 55;
            $weekOfTheCompany = Redis::get('company:small:' . $weekOfTheCompanyId);
            $data = json_decode($weekOfTheCompany);
            if(!is_null($data))
            {
                $data->isFollowing = Redis::sIsMember("followers:profile:".$loggedInProfileId,"company.".$data->id) === 1;
                $data->logo = isset($chefOfTheWeekCompanyData->data_json->image) ? $chefOfTheWeekCompanyData->data_json->image : $data->logo;
                $data->logo_meta = isset($chefOfTheWeekCompanyData->data_json->image_meta) ? json_encode($chefOfTheWeekCompanyData->data_json->image_meta,true) : $data->logo_meta;
                $title = isset($chefOfTheWeekCompanyData->data_json->title) ? $chefOfTheWeekCompanyData->data_json->title : "Company in Focus" ;
                $subtitle = isset($chefOfTheWeekCompanyData->data_json->subtitle) ? $chefOfTheWeekCompanyData->data_json->subtitle : null ;
                $description = isset($chefOfTheWeekCompanyData->data_json->description) ? $chefOfTheWeekCompanyData->data_json->description : null ;
                $data = [$data];
                $model[] = ['title'=>$title, "subtitle"=>$subtitle,"description"=>$description, "type"=>"company","item"=>$data,"ui_type"=>4,"color_code"=>"rgb(255, 255, 255)","is_see_more"=>0];
            }
        }

            /* ui type = 4 is end */



            /* ui type = 5 is end */
            $companiesToFollow = \DB::table('constant_variable_model')->where('ui_type',5)->where('model_name','company')->where('is_active',1)->first();
            if(!is_null($companiesToFollow))
            {
                $companiesToFollow->data_json = json_decode($companiesToFollow->data_json);
                $companyData = \App\Recipe\Company::whereNull('deleted_at')->skip(0)->take(15)->inRandomOrder()->get();
                $data = $companyData;
                $companyData = [];
                foreach($data as $key => &$company){
                    if(is_null($company)){
                        unset($data[$key]);
                        continue;
                    }
                    $company = json_decode($company);
                    $company->isFollowing = Redis::sIsMember("following:profile:" . $loggedInProfileId,"company." . $company->id) === 1;
                    $companyData[] = $company;
                }
                $title = isset($companiesToFollow->data_json->title) ? $companiesToFollow->data_json->title : "Companies to follow" ;
                if(count($companyData))
                    $model[] = ['title'=>$title,'type'=>'company','ui_type'=>5,'item'=>$companyData,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1];

            }
            /* ui type = 5 is end */


            /* ui type = 6 is start */

            $companiesToFollow = \DB::table('constant_variable_model')->where('ui_type',6)->where('model_name','company')->where('is_active',1)->first();
            if(!is_null($companiesToFollow)) {
                $companiesToFollow->data_json = json_decode($companiesToFollow->data_json);
                $companyData = \App\Recipe\Company::whereNull('deleted_at')->skip(0)->take(15)->inRandomOrder()->get();
                $data = $companyData;
                $companyData = [];
                foreach ($data as $key => &$company) {
                    if (is_null($company)) {
                        unset($data[$key]);
                        continue;
                    }
                    $company = json_decode($company);
                    $company->isFollowing = Redis::sIsMember("following:profile:" . $loggedInProfileId, "company." . $company->id) === 1;
                    $companyData[] = $company;
                }
                $title = isset($companiesToFollow->data_json->title) ? $companiesToFollow->data_json->title : "Companies to follow";
                if(count($companyData))
                    $model[] = ['title'=>'More Companies to Follow','type'=>'company','ui_type'=>6,'item'=>$companyData,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1];
            }



            /* ui type = 6 is end */



            /* ui type = 7 is start */

            $collaborationData = \DB::table('constant_variable_model')->where('ui_type',7)->where('model_name','collaborate')->where('is_active',1)->first();
            if(!is_null($collaborationData))
            {
                $collaborationData->data_json = json_decode($collaborationData->data_json);
                $collaborations = Collaborate::where('state',1)->where('collaborate_type','!=','product-review')->skip(0)->take(5)->inRandomOrder()->get();
                $title = isset($collaborationData->data_json->title) ? $collaborationData->data_json->title : "Collaborations" ;
                $subtitle = isset($collaborationData->data_json->subtitle) ? $collaborationData->data_json->subtitle : null ;
                if(count($collaborations))
                    $model[] = ['title'=>$title,'subtitle'=>$subtitle,'type'=>'collaborate','ui_type'=>7,'item'=>$collaborations,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1];
            }


            /* ui type = 7 is end */


            /* ui type = 8 is start */


            $collaborationData = \DB::table('constant_variable_model')->where('ui_type',8)->where('model_name','collaborate-private')->where('is_active',1)->first();
            if(!is_null($collaborationData))
            {
                $collaborationData->data_json = json_decode($collaborationData->data_json);
                $collaborations = Collaborate::where('state',1)->where('collaborate_type','=','product-review')->skip(0)->take(5)->inRandomOrder()->get();
                $title = isset($collaborationData->data_json->title) ? $collaborationData->data_json->title : "Collaborations" ;
                $subtitle = isset($collaborationData->data_json->subtitle) ? $collaborationData->data_json->subtitle : null ;
                if(count($collaborations))
                    $model[] = ['title'=>$title,'subtitle'=>$subtitle,'type'=>'collaborate','ui_type'=>8,'item'=>$collaborations,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>1];
            }


            /* ui type = 8 is end */



            /* ui type = 9 is start */

            $ProductData = \DB::table('constant_variable_model')->where('ui_type',9)->where('model_name','public-review')->where('is_active',1)->first();
            if(!is_null($ProductData))
            {
                $ProductData->data_json = json_decode($ProductData->data_json);
                $products = PublicReviewProduct::where('mark_featured',1)->inRandomOrder()->limit(10)->get();
                $recommended = [];
                foreach($products as $product){
                    $meta = $product->getMetaFor($loggedInProfileId);
                    $recommended[] = ['product'=>$product,'meta'=>$meta];
                }
                $title = isset($ProductData->data_json->title) ? $ProductData->data_json->title : "Featured Products" ;
                $subtitle = isset($ProductData->data_json->subtitle) ? $ProductData->data_json->subtitle : null ;
                if(count($recommended))
                    $model[] = ['title'=>$title,'subtitle'=>$subtitle,'item'=>$recommended,
                        'ui_type'=>9,'color_code'=>'rgb(255, 255, 255)','type'=>'product','is_see_more'=>1];


            }

            /* ui type = 9 is end */



            /* ui type = 10 is start */
            $categoryData = \DB::table('constant_variable_model')->where('ui_type',10)->where('model_name','category')->where('is_active',1)->first();
            if(!is_null($ProductData))
            {
                $categoryData->data_json = json_decode($categoryData->data_json);
                $categories = ProductCategory::where('is_active')->get();
                $title = isset($categoryData->data_json->title) ? $categoryData->data_json->title : 'Based on your Interest' ;
                $subtitle = isset($categoryData->data_json->subtitle) ? $categoryData->data_json->subtitle : null ;
                $model[] = ['title'=>$title,'subtitle'=>$subtitle,'item'=>$categories,
                    'ui_type'=>10,'color_code'=>'rgb(255, 255, 255)','type'=>'product','is_see_more'=>1];

            }
            /* ui type = 10 is end */



            /* ui type = 11 is start */
            $ProductData = \DB::table('constant_variable_model')->where('ui_type',11)->where('model_name','public-review')->where('is_active',1)->first();
            if(!is_null($ProductData))
            {
                $ProductData->data_json = json_decode($ProductData->data_json);
                $products = PublicReviewProduct::where('is_active',1)->orderBy('updated_at','desc')->limit(10)->get();
                $recently = [];
                foreach($products as $product){
                    $meta = $product->getMetaFor($loggedInProfileId);
                    $recently[] = ['product'=>$product,'meta'=>$meta];
                }
                $title = isset($ProductData->data_json->title) ? $ProductData->data_json->title : "Newly Added Products" ;
                $subtitle = isset($ProductData->data_json->subtitle) ? $ProductData->data_json->subtitle : null ;
                if(count ($recently) != 0)
                    $model[] = ['title'=>$title,'subtitle'=>$subtitle,'item'=>$recently,
                        'ui_type'=>11,'color_code'=>'rgb(255, 255, 255)','type'=>'product','is_see_more'=>1];

            }
            /* ui type = 11 is end */






            /* ui type = 12 is start */
            $categoryOfTheWeekData = \DB::table('constant_variable_model')->where('ui_type',12)->where('model_name','category')->where('is_active',1)->first();
            if(!is_null($categoryOfTheWeekData))
            {
                $categoryOfTheWeekData->data_json = json_decode($categoryOfTheWeekData->data_json);
                $title = isset($categoryOfTheWeekData->data_json->title) ? $categoryOfTheWeekData->data_json->title : "Category of Week" ;
                $image = isset($categoryOfTheWeekData->data_json->image_meta->original_photo) ? $categoryOfTheWeekData->data_json->image_meta->original_photo : null;
                $description = isset($chefOfTheWeekCompanyData->data_json->description) ? $chefOfTheWeekCompanyData->data_json->description : null ;
                $weekOfTheCategory = [];
                $weekOfTheCategory[] = ["Name"=>$title,"type"=>"category","description"=>null,"image"=>$image];
                $model[] = ['title'=>$title, "subtitle"=>null,"description"=>$description,
                    "type"=>"category","item"=>$weekOfTheCategory,"ui_type"=>12,"color_code"=>"rgb(255, 255, 255)","is_see_more"=>0];

            }

            /* ui type = 12 is end */





            /* ui type = 13 is start */
            $categoryData = \DB::table('constant_variable_model')->where('ui_type',13)->where('model_name','category')->where('is_active',1)->first();
            if(!is_null($categoryData))
            {
                $categoryData->data_json = json_decode($categoryData->data_json);
                $categories = PublicReviewProduct\ProductCategory::where('is_active',1)->inRandomOrder()->limit(10)->get();
                $title = isset($categoryData->data_json->title) ? $categoryData->data_json->title : "Categories" ;
                $subtitle = isset($categoryData->data_json->subtitle) ? $categoryData->data_json->subtitle : null ;
                if($categories->count())
                    $model[] = ['id'=>$categoryData->model_id,'title'=>$title,'subtitle'=>$subtitle,'item'=>$categories,
                        'ui_type'=>13,'color_code'=>'rgb(255, 255, 255)','type'=>'category','is_see_more'=>1];

            }
            /* ui type = 13 is end */




            /* ui type = 14 is start */
            $categoryData = \DB::table('constant_variable_model')->where('ui_type',14)->where('model_name','category')->where('is_active',1)->first();
            if(!is_null($categoryData))
            {
                $categoryData->data_json = json_decode($categoryData->data_json);
                $recommended = PublicReviewProduct\ProductCategory::where('is_active',1)->inRandomOrder()->limit(6)->get();
                $title = isset($categoryData->data_json->title) ? $categoryData->data_json->title : "Featured Category" ;
                $subtitle = isset($categoryData->data_json->subtitle) ? $categoryData->data_json->subtitle : null ;
                if($recommended->count())
                    $model[] = ['title'=>$title,'subtitle'=>$subtitle,'item'=>$recommended,
                        'ui_type'=>14,'color_code'=>'rgb(255, 255, 255)','type'=>'category','is_see_more'=>1];

            }
            /* ui type = 14 is end */





            /* ui type = 15 is start */


            $specializations = \DB::table('specializations')->get();
            $specializationData = \DB::table('constant_variable_model')->where('ui_type',15)->where('model_name','specialization')->where('is_active',1)->first();
            if(!is_null($specializationData))
            {
                $specializationData->data_json = json_decode($specializationData->data_json);
                $title = isset($specializationData->data_json->title) ? $specializationData->data_json->title : "Explore by Specialization" ;
                if(count($specializations))
                    $model[] = ['title'=>$title,'subtitle'=>null,'type'=>'specializations','ui_type'=>15,'item'=>$specializations,'color_code'=>'rgb(255, 255, 255)','is_see_more'=>0];
            }

            /* ui type = 15 is end */




            /* ui type = 16 is start */

            $fbFriendData = \DB::table('constant_variable_model')->where('ui_type',16)->where('model_name','specialization')->where('is_active',1)->first();
            if(!is_null($fbFriendData))
            {
                $fbFriendData->data_json = json_decode($fbFriendData->data_json);
                $title = isset($fbFriendData->data_json->title) ? $fbFriendData->data_json->title : "See your facebook friend" ;
                if(count($profileData))
                    $model[] = ['title'=>$title,'subtitle'=>null,'type'=>'facebook','ui_type'=>16,'item'=>[],'color_code'=>'rgb(247, 247, 247)','is_see_more'=>0];


            }
            /* ui type = 16 is end */

            $model = collect($model)->sortBy('order')->toArray();
            $this->model = $model;

            return $this->sendResponse();
        }

}
