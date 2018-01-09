<?php

namespace App\Http\Controllers\Api;

use App\Company;
use App\SearchClient;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    //aliases added for frontend
    private $models = [
        'collaborate'=> \App\Collaborate::class,
        'recipe' => \App\Recipe::class,
        'recipes' => \App\Recipe::class,
        'profile' => \App\Profile::class,
        'people' => \App\Profile::class,
        'company' => \App\Company::class,
        'companies' => \App\Company::class,
        'job' => \App\Job::class,
        'jobs' => \App\Job::class
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
    
    private function getModels($type, $ids = [], $filters = [],$skip,$take)
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
        else
        {
            return $model::whereIn('id',$ids)->whereNull('deleted_at')->skip($skip)->take($take)->get();

        }
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
    
    private function autocomplete(&$term, $type = null)
    {
        $suggestions = [];
        
        $total = 10;
        
        if(null === $type || "profile" === $type){
            $profiles = \DB::table("profiles")->select("profiles.id","users.name")
                ->join("users",'users.id','=','profiles.user_id')
                ->where("users.name",'like',"%$term%")
                ->whereNull('users.deleted_at')
                ->take($total - 5)->get();
    
            if(count($profiles)){
                foreach($profiles as $profile){
                    $profile->type = "profile";
                    $suggestions[] = (array) $profile;
                }
            }
            
            $count = $total - $profiles->count();
        }
        
        if(null === $type || "company" === $type){
            $companies = \DB::table("companies")->whereNull('companies.deleted_at')
                ->select("companies.id",'name','profiles.id as profile_id')
                ->join("profiles",'companies.user_id','=','profiles.user_id')
                ->where("name",'like',"%$term%")->take($count ?? 10)
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
    
        $suggestions = $this->autocomplete($query,$type);
        \Log::info("search results");
        \Log::info($response['hits']['total'] > 0);
        if($response['hits']['total'] > 0){
            $hits = collect($response['hits']['hits']);
            $hits = $hits->groupBy("_type");


            foreach($hits as $name => $hit){
                $ids = array_merge(array_pluck($suggestions,'id'),$hits->pluck('_id')->toArray());
                $ids = array_unique($ids);
                \Log::info($hits->pluck('_id')->toArray());
                \Log::info($ids);
                $this->model[$name] = $this->getModels($name,$ids,$request->input('filters'),$skip,$take);
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

//            if(isset($this->model['job']))
//            {
//                $jobs = $this->model['job'];
//                $data = [];
//                foreach($jobs as $job){
//                    $data[] = ['job' => $job, 'meta' => $job->getMetaFor($profileId)];
//                }
//                $this->model['job'] = $data;
//            }

//            if(isset($this->model['recipe']))
//            {
//                $recipes = $this->model['recipe'];
//                $data = [];
//                foreach($recipes as $recipe){
//                    $data[] = ['recipe' => $recipe, 'meta' => $recipe->getMetaFor($profileId)];
//                }
//                $this->model['recipe'] = $data;
//
//            }

            if(isset($this->model['collaborate']))
            {
                $collaborates = $this->model['collaborate'];
                \Log::info($collaborates);
                foreach($collaborates as $collaborate){
                    $this->model['collaborate'][] = ['collaboration' => $collaborate, 'meta' => $collaborate->getMetaFor($profileId)];
                }

            }
            
            return $this->sendResponse();

        }
    
    
        $suggestions = $this->getModels($type,array_pluck($suggestions,'id'),[],$skip,$take);
    
        if($suggestions && $suggestions->count()){
            if(!array_key_exists($type,$this->model)){
                $this->model[$type] = [];
            }
            $this->model[$type] = array_merge($this->model[$type],$suggestions->toArray());
        }
        
        if(!empty($this->model)){
            return $this->sendResponse();
        }
        
        return $this->sendResponse("Nothing found.");
    }


}
