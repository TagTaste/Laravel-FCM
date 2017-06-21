<?php

namespace App\Http\Controllers\Api\Profile\Company\Photo;

use App\PhotoLike;
use App\Events\Update;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class PhotoLikeController extends Controller
{
	/**
	 * Variable to model
	 *
	 * @var photo_like
	 */
	protected $model;

	/**
	 * Create instance of controller with Model
	 *
	 * @return void
	 */
	public function __construct(PhotoLike $model)
	{
		$this->model = $model;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request,$profileId,$albumId,$photoId)
	{
		$photoLike = PhotoLike::where('photo_id',$photoId)->count();
		
		return $photoLike;
		
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */


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
            PhotoLike::where('profile_id', $profileId)->where('photo_id', $photoId)->delete();
            $this->model['likeCount'] = \Redis::hIncrBy("photo:" . $photoId . ":meta","like",-1);
    
        } else {
            PhotoLike::create(['profile_id' => $profileId, 'photo_id' => $photoId]);
            $this->model['likeCount'] = \Redis::hIncrBy("photo:" . $photoId . ":meta","like",1);

            $photoProfile=\DB::table("profile_photos")->select('profile_id')->where('photo_id',$photoId)->pluck('profile_id');
            if($photoProfile[0]!=$profileId) {
                event(new Update($photoId, 'Photo', $photoProfile[0],
                    $request->user()->name . " liked your photo."));
            }
        }
        return $this->sendResponse();
	}

	
	
}