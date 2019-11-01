<?php

namespace App\Http\Controllers\Api\Profile\Photo;

use App\Events\Actions\Like;
use App\Http\Controllers\Api\Controller;
use App\PeopleLike;
use App\PhotoLike;
use App\Profile\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

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
        $photoLike = Redis::sIsMember($key,$loggedInProfileId);
        $this->model = [];
        
        if ($photoLike) {
            PhotoLike::where('profile_id', $loggedInProfileId)->where('photo_id', $photoId)->delete();
            Redis::sRem($key,$loggedInProfileId);
            $this->model['liked'] = false;
        } else {
            PhotoLike::create(['profile_id' => $loggedInProfileId, 'photo_id' => $photoId]);
            Redis::sAdd($key,$loggedInProfileId);
            $this->model['liked'] = true;
            $photo = Photo::find($photoId);
            event(new Like($photo, $request->user()->profile));
        }
        $peopleLike = new PeopleLike();
        $this->model['peopleLiked'] = $peopleLike->peopleLike($photoId, 'photo' ,request()->user()->profile->id);
        $this->model['likeCount'] = Redis::sCard($key);
        return $this->sendResponse();
	}

	
	
}