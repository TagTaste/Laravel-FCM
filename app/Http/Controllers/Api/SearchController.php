<?php

namespace App\Http\Controllers\Api;

use App\Company;
use App\PublicReviewProduct;
use App\SearchClient;
use Illuminate\Http\Request;

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
        \Log::info($ids);
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

    public function search(Request $request, $type = null)
    {
        $query = $request->input('q');
        $this->model = [];
        $this->model['suggestions'] = $this->autocomplete($query);
    
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
    
            if(isset($this->model['profile'])){
                $this->model['profile'] = $this->model['profile']->toArray();
                $following = \Redis::sMembers("following:profile:" . $profileId);
                foreach($this->model['profile'] as &$profile){
                    if($profile && isset($profile['id'])){
                        $profile['isFollowing'] = in_array($profile['id'],$following);
                    }

                }
            }
            
            if(isset($this->model['company'])){
                $this->model['company'] = $this->model['company']->toArray();
                foreach($this->model['company'] as $company){
                    $company['isFollowing'] = Company::checkFollowing($profileId,$company['id']);
                }
            }
        }
    
        
        return $this->sendResponse();
    }
    
    public function suggest(Request $request, $type)
    {
        $this->setType($type);
    
        $name = $request->input('description');
        $params = [
            'index' => 'api',
            'type' => $type,
            'body' => [

                'suggest'=> [
                    'namesuggestion' => [
                        'text' => $name,
                        'term' => [
                            'field' => 'description'
                        ]
                    ]
                ]
            ]
        ];

        $client = SearchClient::get();

        $response = $client->search($params);

        return response()->json($response);
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

        if(null == $type || "product" === $type)
        {
            $products = \DB::table('products')->where('name', 'like','%'.$term.'%')->whereNull('deleted_at')->orderBy('name','asc')->skip($skip)
                ->take($take)->get();

            if(count($products)){
                foreach($products as $product){
                    $product->type = "product";
                    $suggestions[] = (array) $product;
                }
            }
        }
    
    
        return $suggestions;
    }
    private function autocomplete(&$term, $type = null)
    {
        $suggestions = [];
        
        if(null === $type || "profile" === $type){
            $profiles = \DB::table("profiles")->select("profiles.id","users.name")
                ->join("users",'users.id','=','profiles.user_id')
                ->where("users.name",'like',"%$term%")
                ->whereNull('users.deleted_at')
                ->whereNull('profiles.deleted_at')
                ->take(5)
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
                ->select("companies.id",'name','profiles.id as profile_id')
                ->join("profiles",'companies.user_id','=','profiles.user_id')
                ->where("name",'like',"%$term%")
                ->take(5)
                ->whereNull('profiles.deleted_at')
                ->whereNull('companies.deleted_at')
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
    
    public function filterAutoComplete(Request $request,$model,$key)
    {
        $term = $request->input('term');
        $filter = "\\App\\Filter\\" . ucfirst($model);
        $this->model = $filter::selectRaw('distinct value')->where('key','like',$key)->where('value','like',"%$term%")
            ->take(20)
            ->get();
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

    public function filterSearch(Request $request, $type = null)
    {
        $query = $request->input('q');
        $profileId = $request->user()->profile->id;
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
        $this->model = [];
    
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        
        if($response['hits']['total'] > 0){
            $hits = collect($response['hits']['hits']);
            $hits = $hits->groupBy("_type");
            
            foreach($hits as $name => $hit){
                $this->model[$name] = [];
                $ids = $hit->pluck('_id')->toArray();
                $searched = $this->getModels($name,$ids,$request->input('filters'),$skip,$take);
    
                $suggestions = $this->filterSuggestions($query,$name,$skip,$take);
                $suggested = collect([]);
                if(!empty($suggestions)){
                    $suggested = $this->getModels($name,array_pluck($suggestions,'id'));
                }
                $this->model[$name] = $searched->merge($suggested)->sortBy('name');
            }


            if(isset($this->model['profile'])){
//                $this->model['profile'] = $this->model['profile']->toArray();
                $following = \Redis::sMembers("following:profile:" . $profileId);
                $profiles = $this->model['profile']->toArray();
                $this->model['profile'] = [];
                foreach($profiles as $profile){
                    if($profile && isset($profile['id'])){
                        $profile['isFollowing'] = in_array($profile['id'],$following);
                    }
                    $this->model['profile'][] = $profile;

                }
            }

            if(isset($this->model['company'])){
//                $this->model['company'] = $this->model['company']->toArray();
                $companies = $this->model['company']->toArray();
                $this->model['company'] = [];
                foreach($companies as $company){
                    $company['isFollowing'] = Company::checkFollowing($profileId,$company['id']);
                    $this->model['company'][] = $company;
                }
            }
            if(isset($this->model['collaborate']))
            {
                $collaborates = $this->model['collaborate'];
                $this->model['collaborate'] = [];
                foreach($collaborates as $collaborate){
                    $this->model['collaborate'][] = ['collaboration' => $collaborate, 'meta' => $collaborate->getMetaFor($profileId)];
                }

            }

            if(isset($this->model['product']))
            {
                $productIds = $this->model['product']->pluck('id');
                $this->model['product'] = PublicReviewProduct::where('id',$productIds)->get();
            }
            
            return $this->sendResponse();

        }
    
        $suggestions = $this->filterSuggestions($query,$type,$skip,$take);
        $suggestions = $this->getModels($type,array_pluck($suggestions,'id'));
    
        if($suggestions && $suggestions->count()){
//            if(!array_key_exists($type,$this->model)){
//                $this->model[$type] = [];
//            }
            $this->model[$type] = $suggestions->toArray();
        }
        
        if(!empty($this->model)){
            if(isset($this->model['profile'])){
//                $this->model['profile'] = $this->model['profile']->toArray();
                $following = \Redis::sMembers("following:profile:" . $profileId);
                $profiles = $this->model['profile'];
                $this->model['profile'] = [];
                foreach($profiles as $profile){
                    if($profile && isset($profile['id'])){
                        $profile['isFollowing'] = in_array($profile['id'],$following);
                    }
                    $this->model['profile'][] = $profile;

                }
            }

            if(isset($this->model['company'])){
//                $this->model['company'] = $this->model['company']->toArray();
                $companies = $this->model['company'];
                $this->model['company'] = [];
                foreach($companies as $company){
                    $company['isFollowing'] = Company::checkFollowing($profileId,$company['id']);
                    $this->model['company'][] = $company;
                }
            }

            if(isset($this->model['collaborate']))
            {
                $collaborates = $this->model['collaborate'];
                $this->model['collaborate'] = [];
                foreach($collaborates as $collaborate){
                    $this->model['collaborate'][] = ['collaboration' => $collaborate, 'meta' => $collaborate->getMetaFor($profileId)];
                }

            }

            if(isset($this->model['product']))
            {
                $productIds = $this->model['product']->pluck('id');
                $this->model['product'] = PublicReviewProduct::where('id',$productIds)->get();
            }

            return $this->sendResponse();
        }
        $this->model = [];
        $this->messages = ['Nothing found.'];
        return $this->sendResponse();
    }

    public function searchForApp(Request $request, $type = null)
    {
        $query = $request->input('q');
        if(isset($query) && !is_null($query) && !empty($query)) {
            $profileId = $request->user()->profile->id;
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
            $this->model = [];

            $page = $request->input('page');
            list($skip,$take) = \App\Strategies\Paginator::paginate($page);

            if($response['hits']['total'] > 0){
                $hits = collect($response['hits']['hits']);
                $hits = $hits->groupBy("_type");

                foreach($hits as $name => $hit){
                    $this->model[$name] = [];
                    $ids = $hit->pluck('_id')->toArray();
                    $searched = $this->getModels($name,$ids,$request->input('filters'),$skip,$take);

                    $suggestions = $this->filterSuggestions($query,$name,$skip,$take);
                    $suggested = collect([]);
                    if(!empty($suggestions)){
                        $suggested = $this->getModels($name,array_pluck($suggestions,'id'));
                    }

                    $this->model[$name] = $searched->merge($suggested)->sortBy('name');
                }


                if(isset($this->model['profile'])){
//                $this->model['profile'] = $this->model['profile']->toArray();
                    $following = \Redis::sMembers("following:profile:" . $profileId);
                    $profiles = $this->model['profile']->toArray();
                    $this->model['profile'] = [];
                    foreach($profiles as $profile){
                        if($profile && isset($profile['id'])){
                            $profile['isFollowing'] = in_array($profile['id'],$following);
                        }
                        $this->model['profile'][] = $profile;

                    }
                }

                if(isset($this->model['company'])){
//                $this->model['company'] = $this->model['company']->toArray();
                    $companies = $this->model['company']->toArray();
                    $this->model['company'] = [];
                    foreach($companies as $company){
                        $company['isFollowing'] = Company::checkFollowing($profileId,$company['id']);
                        $this->model['company'][] = $company;
                    }
                }

//            if(isset($this->model['job']))
//            {
//                $jobs = $this->model['job'];
//                $data = [];
//                foreach($jobs as $job){
//                    $data[] = ['job' => $job, 'meta' => $job->getMetaFor($profileId)];
//                }
//                $this->model['job'] = $data;
//            }

                if(isset($this->model['recipe']))
                {
                    $recipes = $this->model['recipe'];
                    $this->model['recipe'] = [];
                    foreach($recipes as $recipe){
                        $this->model['recipe'][] = $recipe;
                    }

                }

                if(isset($this->model['collaborate']))
                {
                    $collaborates = $this->model['collaborate'];
                    $this->model['collaborate'] = [];
                    foreach($collaborates as $collaborate){
                        $this->model['collaborate'][] = $collaborate;
                    }

                }

                return $this->sendResponse();

            }

            $suggestions = $this->filterSuggestions($query,$type,$skip,$take);
            $suggestions = $this->getModels($type,array_pluck($suggestions,'id'));

            if($suggestions && $suggestions->count()){
//            if(!array_key_exists($type,$this->model)){
//                $this->model[$type] = [];
//            }
                $this->model[$type] = $suggestions->toArray();
            }

            if(!empty($this->model)){
                if(isset($this->model['profile'])){
//                $this->model['profile'] = $this->model['profile']->toArray();
                    $following = \Redis::sMembers("following:profile:" . $profileId);
                    $profiles = $this->model['profile'];
                    $this->model['profile'] = [];
                    foreach($profiles as $profile){
                        if($profile && isset($profile['id'])){
                            $profile['isFollowing'] = in_array($profile['id'],$following);
                        }
                        $this->model['profile'][] = $profile;

                    }
                }

                if(isset($this->model['company'])){
//                $this->model['company'] = $this->model['company']->toArray();
                    $companies = $this->model['company'];
                    $this->model['company'] = [];
                    foreach($companies as $company){
                        $company['isFollowing'] = Company::checkFollowing($profileId,$company['id']);
                        $this->model['company'][] = $company;
                    }
                }

//            if(isset($this->model['job']))
//            {
//                $jobs = $this->model['job'];
//                $data = [];
//                foreach($jobs as $job){
//                    $data[] = ['job' => $job, 'meta' => $job->getMetaFor($profileId)];
//                }
//                $this->model['job'] = $data;
//            }

                if(isset($this->model['recipe']))
                {
                    $recipes = $this->model['recipe'];
                    $this->model['recipe'] = [];
                    foreach($recipes as $recipe){
                        $this->model['recipe'][] = $recipe;
                    }

                }

                if(isset($this->model['collaborate']))
                {
                    $collaborates = $this->model['collaborate'];
                    $this->model['collaborate'] = [];
                    foreach($collaborates as $collaborate){
                        $this->model['collaborate'][] = $collaborate;
                    }

                }

                return $this->sendResponse();
            }
        }
        else {
            $page = $request->input('page');
            list($skip,$take) = \App\Strategies\Paginator::paginate($page);
            $this->model = [];
            $profileId = $request->user()->profile->id;
            $suggestions = $this->getModelsForApp($type,$skip,$take);
            if ($suggestions && $suggestions->count()) {
                $this->model[$type] = $suggestions->toArray();
            }

            if (!empty($this->model)) {
                if (isset($this->model['profile'])) {
//                $this->model['profile'] = $this->model['profile']->toArray();
                    $following = \Redis::sMembers("following:profile:" . $profileId);
                    $profiles = $this->model['profile'];
                    $this->model['profile'] = [];
                    foreach ($profiles as $profile) {
                        if(is_null($profile))
                            continue;
                        if ($profile && isset($profile['id'])) {
                            $profile['isFollowing'] = in_array($profile['id'], $following);
                        }
                        $this->model['profile'][] = $profile;

                    }
                }

                if (isset($this->model['company'])) {
//                $this->model['company'] = $this->model['company']->toArray();
                    $companies = $this->model['company'];
                    $this->model['company'] = [];
                    foreach ($companies as $company) {
                        if(is_null($company))
                            continue;
                        $company['isFollowing'] = Company::checkFollowing($profileId, $company['id']);
                        $this->model['company'][] = $company;
                    }
                }
            }
        }


        return $this->sendResponse();
    }

    private function getModelsForApp($type,$skip = null ,$take = null)
    {
        $model = isset($this->models[$type]) ? new $this->models[$type] : false;

        $model = $model::whereNull('deleted_at');

        if($type == 'collaborate' || $type == 'collaborates' || $type == 'job' || $type == 'jobs')
        {
            $model = $model->orderBy("created_at","desc");
        }

        if(null !== $skip && null !== $take){
            $model = $model->skip($skip)->take($take);
        }

        return $model->get();

    }



}
