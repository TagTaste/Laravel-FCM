<?php

namespace App\Http\Controllers\Api\V2;


use App\Collaborate;
use App\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Api\Controller;
use App\Http\Controllers\Api\V2\FeedController;
use GraphAware\Neo4j\Client\ClientBuilder;

class SuggestionEngineController extends Controller
{
    public function suggestionProfile(Request $request)
    {
        $profile = $request->user()->profile;
        $profile_id = $profile->id;
        $client = ClientBuilder::create()->addConnection('default', config('database.neo4j_uri'))->build();
        $this->model = FeedController::suggestion_by_following($client, $profile, $profile_id);
        // dd($test);
        // dd($profile, $profile_id);

        // $key = 'suggested:'.$modelName.':'.$request->user()->profile->id;
        // $ignoredId = $request->input('id');

        // $this->model = Redis::sRem($key,$ignoredId);

        return $this->sendResponse();
    }
}