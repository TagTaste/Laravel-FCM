<?php

namespace App\Http\Controllers\Api;

use App\Company;
use App\SearchClient;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    //index = db
    //type = table
    //document = row
    //field = column

    public function search(Request $request, $type = null)
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
        if($request->has('type')){
            $params['type'] = $request->input('type');
        }
        $client = SearchClient::get();
    
        $response = $client->search($params);
        $this->model = [];
        if($response['hits']['total'] > 0){
            $hits = collect($response['hits']['hits']);
            $hits = $hits->groupBy("_type");
            
            foreach($hits as $name => $hit){
                $small = $name === 'profile' || $name === 'company' ? "small:" : null;
                foreach($hit->pluck('_id') as $id){
                    $keys[] = "$name:" . $small . "$id";
                }
                $this->model[$name] = \Redis::mget($keys);
            }
            
            //decode json
            foreach($this->model as $type=>&$objects){
                foreach($objects as &$json){
                    $json = json_decode($json,true);
                }
            }
            
            $profileId = $request->user()->profile->id;
    
            if(isset($this->model['profile'])){
                $following = \Redis::sMembers("following:profile:" . $profileId);
                foreach($this->model['profile'] as &$profile){
                    if($profile && isset($profile['id'])){
                        $profile['isFollowing'] = in_array($profile['id'],$following);
                    }
                }
            }
            
            if(isset($this->model['company'])){
                foreach($this->model['company'] as $company){
                    if($company && isset($company['id'])){
                        $company['isFollowing'] = Company::checkFollowing($profileId,$company['id']);
                    }
                }
            }
            
            $this->model['suggestions'] = $this->autocomplete($query);
            
            return $this->sendResponse();
    
        }
        return $this->sendResponse("Nothing found.");
    }
    
    public function suggest(Request $request, $type)
    {
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
    
    private function autocomplete(&$term)
    {
        $suggestions = [];
        
        $total = 10;
        $profiles = \DB::table("profiles")->select("profiles.id","users.name")
                        ->join("users",'users.id','=','profiles.user_id')
                        ->where("users.name",'like',"%$term%")->take($total - 5)->get();
        
        $count = $total - $profiles->count();
        $companies = \DB::table("companies")->whereNull('companies.deleted_at')
            ->select("companies.id",'name','profiles.id as profile_id')
            ->join("profiles",'companies.user_id','=','profiles.user_id')
            ->where("name",'like',"%$term%")->take($count)->get();
        
        if(count($profiles)){
            foreach($profiles as $profile){
                $profile->type = "profile";
                $suggestions[] = (array) $profile;
            }
        }
        
        if(count($companies)){
            foreach($companies as $company){
                $company->type = "company";
                $suggestions[] = (array) $company;
            }
        }
        
        return $suggestions;
    }
    
    public function filterAutoComplete(Request $request,$model,$key)
    {
        $term = $request->input('term');
        $filter = "\\App\\Filter\\" . ucfirst($model);
        $this->model = $filter::selectRaw('distinct value')->where('key','like',$key)->where('value','like',"%$term%")->get();
        return $this->sendResponse();
    }
}
