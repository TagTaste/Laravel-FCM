<?php

namespace App\Http\Controllers\Api;

use App\Events\Action;
use App\Events\Actions\Like;
use App\ModelSubscriber;
use App\PeopleLike;
use App\Shoutout;
use App\ShoutoutLike;
use Illuminate\Http\Request;
use App\Events\Update;
use Illuminate\Support\Facades\Redis;

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
        $key = "meta:shoutout:likes:" . $id;
        $shoutoutLike = Redis::sIsMember($key, $profileId);
        $this->model = [];
        if ($shoutoutLike != null) {
            ShoutoutLike::where('profile_id', $profileId)->where('shoutout_id', $id)->delete();
            Redis::sRem($key, $profileId);
            $this->model['liked'] = false;
        } else {
            ShoutoutLike::create(['profile_id' => $profileId, 'shoutout_id' => $id]);
            Redis::sAdd($key, $profileId);
            $this->model['liked'] = true;
            
            $shoutout = Shoutout::findOrFail($id);
            if ($shoutout->profile_id != $profileId) {
                event(new Like($shoutout, $request->user()->profile, $shoutout->content));
            }
        }
        $peopleLike = new PeopleLike();
        $this->model['peopleLiked'] = $peopleLike->peopleLike($id, "shoutout",request()->user()->profile->id);
        $this->model['likeCount'] = Redis::sCard($key);
    
        return $this->sendResponse();
    }
}
