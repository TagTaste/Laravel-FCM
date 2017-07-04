<?php

namespace App\Http\Controllers\Api;

use App\Chat;
use App\Strategies\Paginator;
use Illuminate\Http\Request;

class ChatController extends Controller
{
	/**
	 * Variable to model
	 *
	 * @var chat
	 */
	protected $model;

	/**
	 * Create instance of controller with Model
	 *
	 * @return void
	 */
	public function __construct(Chat $model)
	{
		$this->model = $model;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
	    $profileId = $request->user()->profile->id;
        
        $page = $request->input('page');
        list($skip,$take) = Paginator::paginate($page);
        
        $this->model = Chat::where("profile_id",$profileId)->orWhereHas('members',function($query) use ($profileId) {
            $query->where('profile_id',$profileId);
        })->skip($skip)->take($take)->get();
        
		return $this->sendResponse();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$inputs = $request->all();
		$memberProfileId = $inputs['profile_id'];
		
		//creator
		$inputs['profile_id'] = $request->user()->profile->id;
		$this->model = $this->model->create($inputs);
  
		//add member to chat
        $now = \Carbon\Carbon::now();
        $data[] = ['chat_id'=>$this->model->id,'profile_id'=>$memberProfileId, 'created_at'=>$now->toDateTimeString()];
        $this->model->members()->insert($data);
        
        return $this->sendResponse();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request ,$id)
	{
	    $profileId = $request->user()->profile->id;
        $page = $request->input('page');
        list($skip,$take) = Paginator::paginate($page);
        
        //current user should be part of the chat, is a sufficient condition.
        $this->model = Chat::where('id',$id)->whereHas('members',function($query) use ($profileId) {
            $query->where('profile_id',$profileId);
        })->skip($skip)->take($take)->get();
        
        return $this->sendResponse();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @param Request $request
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$inputs = $request->all();

		$chat = $this->model->findOrFail($id);		
		$this->model = $chat->update($inputs);

		return $this->sendResponse();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->model = $this->model->destroy($id);

		return $this->sendResponse();
	}
    
    public function rooms(Request $request)
    {
        $profileId = $request->user()->profile->id;
        $this->model = Chat::without(['members'])->select("chats.id")->where("profile_id",$profileId)->orWhereHas('members',function($query) use ($profileId) {
            $query->where('profile_id',$profileId);
        })->get();
        
        return $this->sendResponse();
	}
}