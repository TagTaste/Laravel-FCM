<?php

namespace App\Http\Controllers\Api\Profile\Photo;

use App\Http\Controllers\Api\Controller;
use App\Photo;
use App\PhotoLike;
use App\Events\Update;
use Illuminate\Http\Request;

class PhotoLikeController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request,$profileId,$albumId,$photoId)
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
        $profileId = $request->user()->profile->id;
        $photoLike = PhotoLike::where('profile_id', $profileId)->where('photo_id', $photoId)->first();
        if($photoLike != null) {
            $photoLike->delete();
            $this->model['likeCount'] = \Redis::hIncrBy("photo:" . $photoId . ":meta","like",-1);
    
        } else {
            PhotoLike::create(['profile_id' => $profileId, 'photo_id' => $photoId]);
            $this->model['likeCount'] = \Redis::hIncrBy("photo:" . $photoId . ":meta","like",1);

            $photoProfile=\DB::table("profile_photos")->select('profile_id')->where('photo_id',$photoId)->pluck('profile_id');
            if($photoProfile[0]!=$profileId) {
                event(new Update($photoId, 'Photo', $photoProfile[0],
                    "like"));
            }
    
        }
        return $this->sendResponse();
	}

	
	
}