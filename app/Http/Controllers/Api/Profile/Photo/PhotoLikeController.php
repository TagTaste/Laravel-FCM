<?php

namespace App\Http\Controllers\Api\Profile\Photo;

use App\Http\Controllers\Api\Controller;
use App\PhotoLike;
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
            $this->model = $photoLike->delete();
            \Redis::hIncrBy("photo:" . $photoId . ":meta","like",-1);
    
        } else {
            $this->model = PhotoLike::create(['profile_id' => $profileId, 'photo_id' => $photoId]);
            \Redis::hIncrBy("photo:" . $photoId . ":meta","like",1);
    
        }
        return $this->sendResponse();
	}

	
	
}