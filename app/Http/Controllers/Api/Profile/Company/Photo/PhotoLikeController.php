<?php

namespace App\Http\Controllers\Api\Profile\Company\Photo;

use App\PeopleLike;
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
        $key = "meta:photo:likes:" . $photoId;
        $photoLike = \Redis::sIsMember($key,$profileId);
        $this->model = [];
        if($photoLike) {
            PhotoLike::where('profile_id', $profileId)->where('photo_id', $photoId)->delete();
            \Redis::sRem($key,$profileId);
            $this->model['liked'] = false;
            $this->model['likeCount'] = \Redis::sCard($key);
        } else {
            PhotoLike::create(['profile_id' => $profileId, 'photo_id' => $photoId]);
            \Redis::sAdd($key,$profileId);
            $this->model['likeCount'] = \Redis::sCard($key);
            $this->model['liked'] = true;

            $photoProfile=\DB::table("profile_photos")->select('profile_id')->where('photo_id',$photoId)->pluck('profile_id');
            if($photoProfile[0]!=$profileId) {
                event(new Update($photoId, 'Photo', $photoProfile[0],
                    "like"));
            }
        }
        $peopleLike = new PeopleLike();
        $this->model['peopleLiked'] = $peopleLike->peopleLike($photoId, 'photo' ,request()->user()->profile->id);
        return $this->sendResponse();
	}

	
	
}