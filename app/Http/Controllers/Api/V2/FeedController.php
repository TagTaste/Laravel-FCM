<?php

namespace App\Http\Controllers\Api\V2;

use App\Channel\Payload;
use App\Strategies\Paginator;
use App\SuggestionEngine;
use App\Education;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Facades\Redis;
use GraphAware\Neo4j\Client\ClientBuilder;

class FeedController extends Controller
{
    protected $model = [];
    //things that is displayed on my (private) feed, and not on network or public
    public function feed(Request $request)
    {
        $page = $request->input('page');
        if($page > 20)
        {
            $this->errors[] = 'No more feed';
            return $this->sendResponse();
        }
        list($skip,$take) = Paginator::paginate($page, 16);
        
        $profileId = $request->user()->profile->id;
        $payloads = Payload::join('subscribers','subscribers.channel_name','=','channel_payloads.channel_name')
            ->where('subscribers.profile_id',$profileId)
            //Query Builder's where clause doesn't work here for some reason.
            //Don't remove this where query.
            //Ofcourse, unless you know what you are doing.
//            ->whereRaw(\DB::raw('channel_payloads.created_at >= subscribers.created_at'))
            ->orderBy('channel_payloads.created_at','desc')
            ->skip($skip)
            ->take($take)
            ->get();
        if($payloads->count() === 0){
            $this->errors[] = 'No more feed';
            return $this->sendResponse();
        }
        $this->getMeta($payloads, $profileId, $request->user()->profile);
        return $this->sendResponse();
    }
    
    //things that is displayed on my public feed
    public function public(Request $request, $profileId)
    {
        $page = $request->input('page',1);
        $take = 20;
        $skip = $page > 1 ? ($page - 1) * $take : 0;
        
        $payloads = Payload::where('channel_name','public.' . $profileId)
            ->orderBy('created_at','desc')
            ->skip($skip)
            ->take($take)
            ->get();
        $profileId = $request->user()->profile->id;
        $this->getMeta($payloads,$profileId);
    
        return $this->sendResponse();
    }
    
    //things that are posted by my network
    public function network(Request $request)
    {
        $page = $request->input('page',1);
        $take = 20;
        $skip = $page > 1 ? ($page - 1) * $take : 0;
        $profileId = $request->user()->profile->id;
        $payloads = Payload::join('subscribers','subscribers.channel_name','=','channel_payloads.channel_name')
            ->where('subscribers.profile_id',$profileId)
            //not my things, but what others have posted.
            ->where('subscribers.channel_name','not like','feed.' . $profileId)
            ->where('subscribers.channel_name','not like','public.' . $profileId)
            ->where('subscribers.channel_name','not like','network.' . $profileId)
    
            //Query Builder's where clause doesn't work here for some reason.
            //Don't remove this where query.
            //Ofcourse, unless you know what you are doing.
            ->whereRaw(\DB::raw('channel_payloads.created_at >= subscribers.created_at'))
            ->skip($skip)
            ->take($take)
            ->get();
        
        $this->getMeta($payloads,$profileId);
    
        return $this->sendResponse();
    }
    
    private function getMeta(&$payloads, &$profileId, $profile)
    {
        $this->model = array_fill(0, 20, null);
        $client = ClientBuilder::create()->addConnection('default', config('database.neo4j_uri'))->build();

        $suggestion_position = array();
        $suggestion_position[] = rand(1,4);
        $suggestion_position[] = rand(6,10);
        $suggestion_position[] = rand(12,15);
        $suggestion_position[] = rand(17,19);

        $feed_position = array_values(array_diff(array_keys($this->model),$suggestion_position));

        $random = range(0,7);
        shuffle($random);
        $random_suggestion = array_slice($random,0,3);
        foreach ($random_suggestion as $key => $value) {
            switch ($value) {
                case '0':
                    $this->model[$suggestion_position[$key]] = $this->suggestion_by_dob($client, $profile, $profileId);
                    break;
                case '1':
                    $this->model[$suggestion_position[$key]] = $this->suggestion_by_foodie_type($client, $profile, $profileId);
                    break;
                case '2':
                    $this->model[$suggestion_position[$key]] = $this->suggestion_by_cuisine($client, $profile, $profileId);
                    break;
                case '3':
                    $this->model[$suggestion_position[$key]] = $this->suggestion_by_education($client, $profile, $profileId);
                    break;
                case '4':
                    $this->model[$suggestion_position[$key]] = $this->suggestion_by_experiance($client, $profile, $profileId);
                    break;
                case '5':
                    $this->model[$suggestion_position[$key]] = $this->suggestion_by_specialization($client, $profile, $profileId);
                    break;
                case '6':
                    $this->model[$suggestion_position[$key]] = $this->suggestion_by_company($client, $profile, $profileId);
                    break;
                case '7':
                    $this->model[$suggestion_position[$key]] = $this->suggestion_by_following($client, $profile, $profileId);
                    break;
                default:
                    break;
            }
        }
        $this->model[$suggestion_position[3]] = $this->suggestion_company($client, $profile, $profileId);
        
        $indexTypeV2 = array("shared", "company", "sharedBy", "shoutout", "profile", "collaborate");
        $indexTypeV1 = array("photo", "polling");
        $index = 0;
        foreach ($payloads as $payload) {
            $type = null;
            $data = [];
            $cached = json_decode($payload->payload, true);
            foreach ($cached as $name => $key) {
                $cachedData = null;
                if (in_array($name, $indexTypeV2)) {
                    $key = $key.":V2";
                    $cachedData = Redis::connection('V2')->get($key);
                } else {
                    $cachedData = Redis::get($key);
                }
                if (!$cachedData) {
                    \Log::warning("could not get from $key");
                }
                $data[$name] = json_decode($cachedData,true);
            }


            if ($payload->model !== null) {
                $model = $payload->model;
                $type = $this->getType($payload->model);
                $model = $model::find($payload->model_id);
                if ($model !== null && method_exists($model, 'getMetaForV2')) {
                    $data['meta'] = $model->getMetaForV2($profileId);
                }
            }
            $data['type'] = $type;
            $this->model[$feed_position[$index++]] = $data;
        }
        $this->model = array_values(array_filter($this->model));
    }

    private function suggestion_by_dob($client, $profile, $profileId) 
    {
        // birthday suggestion
        $suggestion = array(
            "suggestion" => array(),
            "meta" => [
                "count" => 0,
                "text" => "Profile born on same day.",
                "sub_type" => "profile",
            ],
            "type" => "suggestion",
        );
        $time = strtotime($profile->dob);
        if (!is_null($time)) {
            $date = date('d-m',$time);
            $query = "MATCH (:DateOfBirth {dob: '$date'})-[:HAVE]-(users:User), (user:User {profile_id:$profileId})
                WHERE users.profile_id <> $profileId AND not ((user)-[:FOLLOWS {following:1}]->(users))
                WITH users, rand() AS number
                RETURN users
                ORDER BY number
                LIMIT 10";
            $result = $client->run($query);
            foreach ($result->records() as $record) {
                $suggestion["meta"]["count"]++;
                array_push($suggestion["suggestion"], $record->get('users')->values());
            } 
        }
        return $suggestion;   
    }

    private function suggestion_by_foodie_type($client, $profile, $profileId) 
    {
        // birthday suggestion
        $suggestion = array(
            "suggestion" => array(),
            "meta" => [
                "count" => 0,
                "text" => "Profile having same eating habit.",
                "sub_type" => "profile",
            ],
            "type" => "suggestion",
        );
        $foodie_type = $profile->foodieType;
        if (!is_null($foodie_type)) {
            $foodie_type_id = $foodie_type->id;
            $query = "MATCH (:FoodieType {foodie_type_id: $foodie_type_id})-[:HAVE]-(users:User), (user:User {profile_id:$profileId})
                WHERE users.profile_id <> $profileId AND not ((user)-[:FOLLOWS {following:1}]->(users))
                WITH users, rand() AS number
                RETURN users
                ORDER BY number
                LIMIT 10";
            $result = $client->run($query);
            foreach ($result->records() as $record) {
                $suggestion["meta"]["count"]++;
                array_push($suggestion["suggestion"], $record->get('users')->values());
            } 
        }
        return $suggestion;   
    }

    private function suggestion_by_cuisine($client, $profile, $profileId) 
    {
        // birthday suggestion
        $suggestion = array(
            "suggestion" => array(),
            "meta" => [
                "count" => 0,
                "text" => "Profile having same cuisine.",
                "sub_type" => "profile",
            ],
            "type" => "suggestion",
        );
        $cuisine_ids = $profile->cuisines->pluck('id')->toArray();
        if (count($cuisine_ids)) {
            $cuisine_ids_string = implode(',',$cuisine_ids);
            $query = "MATCH (cuisine:Cuisines)-[:HAVE]-(users:User), (user:User {profile_id:$profileId})
                WHERE cuisine.cuisine_id IN [$cuisine_ids_string] AND users.profile_id <> $profileId AND not ((user)-[:FOLLOWS {following:1}]->(users))
                WITH users, rand() AS number
                RETURN users
                ORDER BY number
                LIMIT 10";
            $result = $client->run($query);
            foreach ($result->records() as $record) {
                $suggestion["meta"]["count"]++;
                array_push($suggestion["suggestion"], $record->get('users')->values());
            } 
        }
        return $suggestion;   
    }

    private function suggestion_by_education($client, $profile, $profileId) 
    {
        // birthday suggestion
        $suggestion = array(
            "suggestion" => array(),
            "meta" => [
                "count" => 0,
                "text" => "Profile having same degree.",
                "sub_type" => "profile",
            ],
            "type" => "suggestion",
        );
        $education_list = $profile->education->pluck('degree')->toArray();
        if (count($education_list)) {
            $education_list = array_filter(
                $education_list, 
                function($value) { 
                    return !in_array($value, ["", null]); 
                }
            );
            foreach ($education_list as $key => $value) {
                $education = new Education();
                $degree = $education->seo_friendly_url($value);
                $education_list[$key] = $education->remove_unwanted_info($degree);
            }
        }
        $education_list_string = "'" . implode ( "', '", $education_list ) . "'";
        $query = "MATCH (degree:Degree)-[:HAVE]-(users:User), (user:User {profile_id:$profileId})
            WHERE degree.name IN [$education_list_string] AND users.profile_id <> $profileId AND not ((user)-[:FOLLOWS {following:1}]->(users))
            WITH users, rand() AS number
            RETURN users
            ORDER BY number
            LIMIT 10";
        $result = $client->run($query);
        foreach ($result->records() as $record) {
            $suggestion["meta"]["count"]++;
            array_push($suggestion["suggestion"], $record->get('users')->values());
        } 
        return $suggestion;   
    }

    private function suggestion_by_experiance($client, $profile, $profileId) 
    {
        // birthday suggestion
        $suggestion = array(
            "suggestion" => array(),
            "meta" => [
                "count" => 0,
                "text" => "Profile having same job profile.",
                "sub_type" => "profile",
            ],
            "type" => "suggestion",
        );
        $experiance_list = $profile->experience->pluck('designation')->toArray();
        if (count($experiance_list)) {
            $experiance_list = array_filter(
                $experiance_list, 
                function($value) { 
                    return !in_array($value, ["", null]); 
                }
            );
            foreach ($experiance_list as $key => $value) {
                $experiance = new \App\Profile\Experience();
                $designation = $experiance->seo_friendly_url($value);
                $experiance_list[$key] = $experiance->remove_unwanted_info($designation);
            }
        }
        $experiance_list_string = "'" . implode ( "', '", $experiance_list ) . "'";
        $query = "MATCH (experiance:Experiance)-[:HAVE]-(users:User), (user:User {profile_id:$profileId})
            WHERE experiance.name IN [$experiance_list_string] AND users.profile_id <> $profileId AND not ((user)-[:FOLLOWS {following:1}]->(users))
            WITH users, rand() AS number
            RETURN users
            ORDER BY number
            LIMIT 10";
        $result = $client->run($query);
        foreach ($result->records() as $record) {
            $suggestion["meta"]["count"]++;
            array_push($suggestion["suggestion"], $record->get('users')->values());
        } 
        return $suggestion;   
    }

    private function suggestion_by_specialization($client, $profile, $profileId) 
    {
        $suggestion = array(
            "suggestion" => array(),
            "meta" => [
                "count" => 0,
                "text" => "Profile having same job specialization.",
                "sub_type" => "profile",
            ],
            "type" => "suggestion",
        );
        $specialization_ids = $profile->profile_specializations->pluck('id')->toArray();
        if (count($specialization_ids)) {
            $specialization_ids_string = implode(',',$specialization_ids);
            $query = "MATCH (specialization:Specializations)-[:HAVE]-(users:User), (user:User {profile_id:$profileId})
                WHERE specialization.specialization_id IN [$specialization_ids_string] AND users.profile_id <> $profileId AND not ((user)-[:FOLLOWS {following:1}]->(users))
                WITH users, rand() AS number
                RETURN users
                ORDER BY number
                LIMIT 10";
            $result = $client->run($query);
            foreach ($result->records() as $record) {
                $suggestion["meta"]["count"]++;
                array_push($suggestion["suggestion"], $record->get('users')->values());
            }
        }
        return $suggestion;
    }

    private function suggestion_by_company($client, $profile, $profileId) 
    {
        $suggestion = array(
            "suggestion" => array(),
            "meta" => [
                "count" => 0,
                "text" => "Profile following same company.",
                "sub_type" => "profile",
            ],
            "type" => "suggestion",
        );
        
        $query = "MATCH (user:User {profile_id:$profileId})-[:FOLLOWS_COMPANY]-(company:Company)<-[:FOLLOWS_COMPANY]-(users:User)
            WHERE users.profile_id <> $profileId AND not ((user)-[:FOLLOWS {following:1}]->(users))
            WITH users, rand() AS number
            RETURN users
            ORDER BY number
            LIMIT 10";
        $result = $client->run($query);
        foreach ($result->records() as $record) {
            $suggestion["meta"]["count"]++;
            array_push($suggestion["suggestion"], $record->get('users')->values());
        }
        
        return $suggestion;
    }

    private function suggestion_by_following($client, $profile, $profileId) 
    {
        $suggestion = array(
            "suggestion" => array(),
            "meta" => [
                "count" => 0,
                "text" => "Profile following sub followers.",
                "sub_type" => "profile",
            ],
            "type" => "suggestion",
        );
        
        $query = "MATCH (user:User {profile_id:$profileId})-[:FOLLOWS]-(users:User)<-[:FOLLOWS]-(sub_users:User)   
            WHERE sub_users.profile_id <> $profileId AND not ((user)-[:FOLLOWS {following:1}]->(sub_users))
            WITH sub_users, rand() AS number
            RETURN sub_users
            ORDER BY number
            LIMIT 10";
        $result = $client->run($query);
        foreach ($result->records() as $record) {
            $suggestion["meta"]["count"]++;
            array_push($suggestion["suggestion"], $record->get('sub_users')->values());
        }
        
        return $suggestion;
    }

    private function suggestion_company($client, $profile, $profileId) 
    {
        $suggestion = array(
            "suggestion" => array(),
            "meta" => [
                "count" => 0,
                "text" => "Company suggestion.",
                "sub_type" => "company",
            ],
            "type" => "suggestion",
        );
        
        $query = "MATCH (user:User {profile_id:$profileId}), (company:Company)
            WHERE not ((user)-[:FOLLOWS_COMPANY {following:1}]->(company))
            WITH company, rand() AS number
            RETURN company
            ORDER BY number
            LIMIT 10";
        $result = $client->run($query);
        foreach ($result->records() as $record) {
            $suggestion["meta"]["count"]++;
            array_push($suggestion["suggestion"], $record->get('company')->values());
        }
        return $suggestion;
    }

    private function getType($modelName)
    {
        $exploded = explode('\\',$modelName);
        return strtolower(end($exploded));
    }
    //things that is displayed on company's public feed
    public function company(Request $request, $companyId)
    {

        $page = $request->input('page',1);
        $take = 20;
        $skip = $page > 1 ? ($page - 1) * $take : 0;

        $payloads = Payload::where('channel_name','company.public.' . $companyId)
            ->orderBy('created_at','desc')
            ->skip($skip)
            ->take($take)
            ->get();
        $profileId=$request->user()->profile->id;
        $this->getMeta($payloads,$profileId);

        return $this->sendResponse();
    }
  
}
