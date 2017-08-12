<?php

namespace App\Http\Controllers\Api;

use App\Events\Action;
use App\Events\Actions\Like;
use App\ModelSubscriber;
use App\Shoutout;
use App\ShoutoutLike;
use Illuminate\Http\Request;
use App\Events\Update;

class ShoutoutLikeController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request, $id)
    {
        $this->model = ShoutoutLike::where('shoutout_id', $id)->count();
        return $this->sendResponse();
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request, $id)
    {
        $profileId = $request->user()->profile->id;
        $shoutoutlike = ShoutoutLike::where('profile_id', $profileId)->where('shoutout_id', $id)->first();
        $this->model = [];
        if ($shoutoutlike != null) {
            $shoutoutlike->delete();
            $this->model['liked'] = true;
            $this->model['likeCount'] = \Redis::hIncrBy("shoutout:" . $id . ":meta", "like", -1);
        } else {
            
            ShoutoutLike::create(['profile_id' => $profileId, 'shoutout_id' => $id]);
            $this->model['liked'] = false;
            $this->model['likeCount'] = \Redis::hIncrBy("shoutout:" . $id . ":meta", "like", 1);
            
            $shoutout = Shoutout::findOrFail($id);
            event(new Like($shoutout, $request->user()->profile, $shoutout->content));
        }
        
        return $this->sendResponse();
    }
    
}