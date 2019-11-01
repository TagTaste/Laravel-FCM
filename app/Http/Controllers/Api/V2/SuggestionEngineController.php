<?php

namespace App\Http\Controllers\Api\V2;


use App\Collaborate;
use App\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Api\Controller;
use App\Http\Controllers\Api\V2\FeedController;
use GraphAware\Neo4j\Client\ClientBuilder;
use App\Strategies\Paginator;
use App\Advertisements;
use Carbon\Carbon;

class SuggestionEngineController extends Controller
{
    public function suggestionProfile(Request $request)
    {
        $profile = $request->user()->profile;
        $profile_id = $profile->id;
        $client = ClientBuilder::create()->addConnection('default', config('database.neo4j_uri'))->build();
        $this->model = FeedController::suggestion_by_following($client, $profile, $profile_id);
        return $this->sendResponse();
    }

    public function suggestionCompany(Request $request)
    {
        $profile = $request->user()->profile;
        $profile_id = $profile->id;
        $client = ClientBuilder::create()->addConnection('default', config('database.neo4j_uri'))->build();
        $this->model = FeedController::suggestion_company($client, $profile, $profile_id);
        return $this->sendResponse();
    }

    public function adEngineList(Request $request) 
    {
        $profile = $request->user()->profile;
        $profileId = $profile->id;

        $final_data = array();
        $page = $request->input('page');
        list($skip,$take) = Paginator::paginate($page, 10);

        $advertisements = Advertisements::whereNull('deleted_at')->where('is_active',1)
            ->whereDate('expired_at', '>', Carbon::now())
            ->orderBy('created_at', 'desc')
            ->skip($skip)
            ->take($take)
            ->get();

        if (count($advertisements)) {
            foreach ($advertisements as $key => $advertisement_detail) {
                $card = array(
                    "advertisement" => [],
                    "meta" => [
                        "count" => 0,
                        "text" => "Promoted",
                        "sub_type" => null,
                    ],
                    "type" => "advertisement",
                );

                $advertisement = $advertisement_detail->toArray();
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
                array_push($final_data, $card);
            }
        }
        $this->model = $final_data;
        return $this->sendResponse();
    }
}