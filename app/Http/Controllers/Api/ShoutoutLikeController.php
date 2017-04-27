<?php

namespace App\Http\Controllers\Api;

use App\ShoutoutLike;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Profile;

class ShoutoutLikeController extends Controller
{
	/**
	 * Variable to model
	 *
	 * @var shoutout_like
	 */
	protected $model;

	/**
	 * Create instance of controller with Model
	 *
	 * @return void
	 */
	public function __construct(ShoutoutLike $model)
	{
		$this->model = $model;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($shoutoutId,Request $request)
	{
		$profileId = $request->user()->profile->id;
		if(Shoutoutlike::where('shoutout_id',$shoutoutId)->where('profile_id',$profileId)->first())
		{
			$shoutoutlike['hasliked'] = true;
		}
		else
		{
			$shoutoutlike['hasliked']= false;
		}
		$shoutoutlike['count'] = ShoutoutLike::where('shoutout_id',$shoutoutId)->count();
		
		
		return $shoutoutlike;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('shoutout_likes.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request,$shoutoutId)
	{
		$profileId = $request->user()->profile->id;
		$shoutoutlike = ShoutoutLike::where('profile_id',$profileId)->where('shoutout_id',$shoutoutId)->first();
		if($shoutoutlike != null)
			{
				$shoutoutlike->delete();
				return 0;
			}
			else
			{
				$shoutoutlike = ShoutoutLike::create(['profile_id' => $profileId, 'shoutout_id' => $shoutoutId]);
				return 1;
			}
			
		

		
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	
}