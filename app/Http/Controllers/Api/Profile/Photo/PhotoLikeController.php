<?php

namespace App\Http\Controllers\Api\Profile\Photo;

use App\Events\Actions\Like;
use App\Http\Controllers\Api\Controller;
use App\Profile\Photo;
use App\PhotoLike;
use App\Events\Update;
use Illuminate\Http\Request;

class
PhotoLikeController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request,$profileId,$photoId)
	{
		return PhotoLike::where('photo_id',$photoId)->count();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request, $profileId, $photoId)
	{
        $loggedInProfileId = $request->user()->profile->id;
        
        $key = "meta:photo:likes:" . $photoId;
        $photoLike = \Redis::sMember($key,$loggedInProfileId);
        $this->model = [];
        
        if ($photoLike) {
            $photoLike->delete();
            \Redis::sRem($key,$loggedInProfileId);
            $this->model['liked'] = false;
        } else {
            PhotoLike::create(['profile_id' => $loggedInProfileId, 'photo_id' => $photoId]);
            \Redis::sAdd($key,$loggedInProfileId);
            $this->model['liked'] = true;
            event(new Like($photo, $request->user()->profile));
        }
        
        $this->model['likeCount'] = \Redis::sCard($key);
        return $this->sendResponse();
	}

	
	
}