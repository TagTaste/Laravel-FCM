<?php

namespace App\Http\Controllers\Api\Profile\Album\Photo;

use App\PhotoLike;
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
	public function store(Request $request, $profileId, $albumId, $photoId)
	{
        $profileId = $request->user()->profile->id;
        $photoLike = PhotoLike::where('profile_id', $profileId)->where('photo_id', $photoId)->first();
        if($photoLike != null) {
            $this->model = PhotoLike::where('profile_id', $profileId)->where('photo_id', $photoId)->delete();
        } else {
            $this->model = PhotoLike::create(['profile_id' => $profileId, 'photo_id' => $photoId]);
        }
        return $this->sendResponse();
	}

	
	
}