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
use App\Collaborate;
use App\PublicReviewProduct;
use App\Advertisements;
use Carbon\Carbon;

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
        list($skip,$take) = Paginator::paginate($page, 15);
        
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

        // 2 profile, 6 product, 10 company, 13 ad engine and 15 collaboration suggestion
        $suggestion_position = array(2, 6, 10, 13, 15);

        // $suggestion_position = array();
        // $suggestion_position[] = rand(2,4);
        // $suggestion_position[] = rand(6,8);
        // $suggestion_position[] = rand(10,12);
        // $suggestion_position[] = rand(14,16);
        // $suggestion_position[] = rand(18,20);

        $feed_position = array_values(array_diff(array_keys($this->model),$suggestion_position));

        $random = range(0,7);
        shuffle($random);
        $random_suggestion = array_slice($random,0,1);
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
        $this->model[$suggestion_position[1]] = $this->suggestion_products($client, $profile, $profileId);
        $this->model[$suggestion_position[2]] = $this->suggestion_company($client, $profile, $profileId);
        $this->model[$suggestion_position[3]] = $this->ad_engine($client, $profile, $profileId);
        $this->model[$suggestion_position[4]] = $this->suggestion_collaboration($client, $profile, $profileId);

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

    public static function suggestion_by_dob($client, $profile, $profileId) 
    {
        // birthday suggestion
        $suggestion = array(
            "suggestion" => array(),
            "meta" => [
                "count" => 0,
                "text" => "Suggested for you",
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
                ORDER BY number
                RETURN DISTINCT users
                LIMIT 10";
            $result = $client->run($query);
            foreach ($result->records() as $record) {
                $suggestion["meta"]["count"]++;
                array_push($suggestion["suggestion"], $record->get('users')->values());
            } 
        }
        return $suggestion;   
    }

    public static function suggestion_by_foodie_type($client, $profile, $profileId) 
    {
        // birthday suggestion
        $suggestion = array(
            "suggestion" => array(),
            "meta" => [
                "count" => 0,
                "text" => "Suggested for you",
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
                ORDER BY number
                RETURN DISTINCT users
                LIMIT 10";
            $result = $client->run($query);
            foreach ($result->records() as $record) {
                $suggestion["meta"]["count"]++;
                array_push($suggestion["suggestion"], $record->get('users')->values());
            } 
        }
        return $suggestion;   
    }

    public static function suggestion_by_cuisine($client, $profile, $profileId) 
    {
        // birthday suggestion
        $suggestion = array(
            "suggestion" => array(),
            "meta" => [
                "count" => 0,
                "text" => "Suggested for you",
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
                ORDER BY number
                RETURN DISTINCT users
                LIMIT 10";
            $result = $client->run($query);
            foreach ($result->records() as $record) {
                $suggestion["meta"]["count"]++;
                array_push($suggestion["suggestion"], $record->get('users')->values());
            } 
        }
        return $suggestion;   
    }

    public static function suggestion_by_education($client, $profile, $profileId) 
    {
        // birthday suggestion
        $suggestion = array(
            "suggestion" => array(),
            "meta" => [
                "count" => 0,
                "text" => "Suggested for you",
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
            ORDER BY number
            RETURN DISTINCT users
            LIMIT 10";
        $result = $client->run($query);
        foreach ($result->records() as $record) {
            $suggestion["meta"]["count"]++;
            array_push($suggestion["suggestion"], $record->get('users')->values());
        } 
        return $suggestion;   
    }

    public static function suggestion_by_experiance($client, $profile, $profileId) 
    {
        // birthday suggestion
        $suggestion = array(
            "suggestion" => array(),
            "meta" => [
                "count" => 0,
                "text" => "Suggested for you",
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
            ORDER BY number
            RETURN DISTINCT users
            LIMIT 10";
        $result = $client->run($query);
        foreach ($result->records() as $record) {
            $suggestion["meta"]["count"]++;
            array_push($suggestion["suggestion"], $record->get('users')->values());
        } 
        return $suggestion;   
    }

    public static function suggestion_by_specialization($client, $profile, $profileId) 
    {
        $suggestion = array(
            "suggestion" => array(),
            "meta" => [
                "count" => 0,
                "text" => "Suggested for you",
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
                ORDER BY number
                RETURN DISTINCT users
                LIMIT 10";
            $result = $client->run($query);
            foreach ($result->records() as $record) {
                $suggestion["meta"]["count"]++;
                array_push($suggestion["suggestion"], $record->get('users')->values());
            }
        }
        return $suggestion;
    }

    public static function suggestion_by_company($client, $profile, $profileId) 
    {
        $suggestion = array(
            "suggestion" => array(),
            "meta" => [
                "count" => 0,
                "text" => "Suggested for you",
                "sub_type" => "profile",
            ],
            "type" => "suggestion",
        );
        
        $query = "MATCH (user:User {profile_id:$profileId})-[:FOLLOWS_COMPANY]-(company:Company)<-[:FOLLOWS_COMPANY]-(users:User)
            WHERE users.profile_id <> $profileId AND not ((user)-[:FOLLOWS {following:1}]->(users))
            WITH users, rand() AS number
            ORDER BY number
            RETURN DISTINCT users
            LIMIT 10";
        $result = $client->run($query);
        foreach ($result->records() as $record) {
            $suggestion["meta"]["count"]++;
            array_push($suggestion["suggestion"], $record->get('users')->values());
        }
        
        return $suggestion;
    }

    public static function suggestion_by_following($client, $profile, $profileId) 
    {
        $suggestion = array(
            "suggestion" => array(),
            "meta" => [
                "count" => 0,
                "text" => "Suggested for you",
                "sub_type" => "profile",
            ],
            "type" => "suggestion",
        );
        
        $query = "MATCH (user:User {profile_id:$profileId})-[:FOLLOWS]-(users:User)<-[:FOLLOWS]-(sub_users:User)   
            WHERE sub_users.profile_id <> $profileId AND not ((user)-[:FOLLOWS {following:1}]->(sub_users))
            WITH sub_users, rand() AS number
            ORDER BY number
            RETURN DISTINCT sub_users
            LIMIT 10";
        $result = $client->run($query);
        foreach ($result->records() as $record) {
            $suggestion["meta"]["count"]++;
            array_push($suggestion["suggestion"], $record->get('sub_users')->values());
        }
        
        return $suggestion;
    }

    public static function suggestion_company($client, $profile, $profileId) 
    {
        $suggestion = array(
            "suggestion" => array(),
            "meta" => [
                "count" => 0,
                "text" => "Suggested for you",
                "sub_type" => "company",
            ],
            "type" => "suggestion",
        );
        
        $query = "MATCH (user:User {profile_id:$profileId}), (company:Company)
            WHERE not ((user)-[:FOLLOWS_COMPANY {following:1}]->(company))
            WITH company, rand() AS number
            ORDER BY number
            RETURN DISTINCT company
            LIMIT 10";
        $result = $client->run($query);
        foreach ($result->records() as $record) {
            $suggestion["meta"]["count"]++;
            array_push($suggestion["suggestion"], $record->get('company')->values());
        }
        return $suggestion;
    }

    public static function suggestion_collaboration($client, $profile, $profileId) 
    {
        $suggestion = array(
            "suggestion" => array(),
            "meta" => [
                "count" => 0,
                "text" => "Suggested for you",
                "sub_type" => "collaborate",
            ],
            "type" => "suggestion",
        );

        $applied_collaboration = \DB::table('collaborate_applicants')
            ->where('profile_id',$profileId)
            ->where('is_invited',0)
            ->whereNull('rejected_at')
            ->pluck('collaborate_id')
            ->toArray();

        $collaborations = Collaborate::where('collaborates.state',Collaborate::$state[0])
            ->whereNotIn('id',$applied_collaboration)
            ->inRandomOrder()
            ->pluck('id')
            ->take(3)
            ->toArray();

        if (count($collaborations)) {
            foreach ($collaborations as $key => $id) {
                $cached_data = Redis::get("collaborate:".$id.":V2");
                if ($cached_data) {
                    $data = json_decode($cached_data,true); 
                    $data["company"] = null;
                    $data["profile"] = null;
                    // add company detail to collaboration
                    if (isset($data['company_id'])) {
                        $company_cached_data = Redis::get("company:small:".$data['company_id'].":V2");
                        if ($company_cached_data) {
                            $data["company"] = json_decode($company_cached_data,true); 
                        } 
                    }

                    // add profile detail to collaboration
                    if (isset($data['profile_id'])) {
                        $company_cached_data = Redis::get("profile:small:".$data['profile_id'].":V2");
                        if ($company_cached_data) {
                            $data["profile"] = json_decode($company_cached_data,true); 
                        } 
                    }

                    $suggestion["meta"]["count"]++;
                    array_push($suggestion["suggestion"], $data); 
                }
            }
        }
        return $suggestion;
    }

    public static function suggestion_products($client, $profile, $profileId) 
    {
        $suggestion = array(
            "suggestion" => array(),
            "meta" => [
                "count" => 0,
                "text" => "Suggested for you",
                "sub_type" => "product",
            ],
            "type" => "suggestion",
        );

        $applied_product_review = \DB::table('public_product_user_review')
            ->where('profile_id',$profileId)
            ->where('current_status',2)
            ->distinct('product_id')
            ->pluck('product_id')
            ->toArray();

        $public_review_product = PublicReviewProduct::where('is_active',1)
            ->whereNotIn('id',$applied_product_review)
            ->inRandomOrder()
            ->get(['id', 'global_question_id'])
            ->take(10);

        if (count($public_review_product)) {
            foreach ($public_review_product as $key => $product) {
                $cached_data = Redis::get("public-review/product:".$product->id.":V2");
                if ($cached_data) {
                    $data = array();
                    $data['product'] = json_decode($cached_data,true); 
                    $data['meta'] = $product->getMetaFor($profileId);
                    if (!is_null($data['meta']) && array_key_exists('overall_rating', $data['meta']) && !is_null($data['meta']['overall_rating'])) {
                        $suggestion["meta"]["count"]++;
                        array_push($suggestion["suggestion"], $data); 
                    }
                }
            }
        }
        return $suggestion;
    }

    public static function ad_engine($client, $profile, $profileId) 
    {
        $card = array(
            "advertisement" => [],
            "meta" => [
                "count" => 0,
                "text" => "Promoted",
                "sub_type" => null,
            ],
            "type" => "advertisement",
        );

        $advertisement_random = Advertisements::whereNull('deleted_at')->where('is_active',1)->whereDate('expired_at', '>', Carbon::now())->inRandomOrder()->first();

        if (count($advertisement_random)) {
            $advertisement = $advertisement_random->toArray();
            $data = [];

            if (2 == $advertisement['type_id']) {
                if (!is_null($advertisement['payload'])) {
                    $cached = json_decode($advertisement['payload'], true);
                    $indexTypeV2 = array("shared", "company", "sharedBy", "shoutout", "profile", "collaborate");
                    $indexTypeV1 = array("photo", "polling");
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

                    if ($advertisement['actual_model'] !== null) {
                        $model = $advertisement['actual_model'];
                        $type = getType($advertisement['actual_model']);
                        $model = $model::find($advertisement['model_id']);
                        if ($model !== null && method_exists($model, 'getMetaForV2')) {
                            $data['meta'] = $model->getMetaForV2($profileId);
                        }
                    }
                    $data['type'] = strtolower($advertisement['model']);
                    $card['meta']['sub_type'] = strtolower($advertisement['model']);
                    $advertisement['payload'] = $data;
                }
            } else if (1 == $advertisement['type_id']) {
                if (!is_null($advertisement['image'])) {
                    $advertisement['image'] = json_decode($advertisement['image']);
                }
                $card['meta']['sub_type'] = "image";
            }

            $card['meta']['count'] = 1; 
            
            foreach ($advertisement as $key => $value) {
                if (is_null($value) || $value == '')
                    unset($advertisement[$key]);
            }
            
            $card['advertisement'] = $advertisement;
        }
        return $card;
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

    // cards that are interacted over feed.
    public function feedInteraction(Request $request, $modelName, $modelId, $device, $interactionTypeId)
    {
        dd($request, $modelName, $modelId, $device, $interactionTypeId);
        $page = $request->input('page');
        if($page > 20)
        {
            $this->errors[] = 'No more feed';
            return $this->sendResponse();
        }
        list($skip,$take) = Paginator::paginate($page, 15);
        
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
  
}
