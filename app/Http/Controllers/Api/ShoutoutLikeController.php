<?php

namespace App\Http\Controllers\Api;

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
		$this->model = ShoutoutLike::where('shoutout_id',$id)->count();
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
        if ($shoutoutlike != null) {
            $this->model = $shoutoutlike->delete();
            \Redis::hIncrBy("shoutout:" . $id . ":meta","like",-1);
        } else {
            $this->model = ShoutoutLike::create(['profile_id' => $profileId, 'shoutout_id' => $id]);
            \Redis::hIncrBy("shoutout:" . $id . ":meta","like",1);

            $shoutoutProfile=Shoutout::findOrFail($id);
            event(new Update($id,'Shoutout',$shoutoutProfile->profile_id,
                $request->user()->name." like you post ".$shoutoutProfile->content));
        }

        return $this->sendResponse();
    }
	
}