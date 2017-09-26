<?php

namespace App\Http\Controllers\Api\Chat;

use App\Chat;
use App\Chat\Member;
use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;

class MemberController extends Controller
{
	/**
	 * Variable to model
	 *
	 * @var chat_member
	 */
	protected $model;

	/**
	 * Create instance of controller with Model
	 *
	 * @return void
	 */
	public function __construct(Member $model)
	{
		$this->model = $model;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request, $chatId)
	{
	    $profileId = $request->user()->profile->id;
	    
	    //check if profileId is member of given $chatId
		$memberOfChat = Member::where('chat_id',$chatId)->where('profile_id',$profileId)->exists();
  
		if(!$memberOfChat){
		    return $this->sendError("Profile is not part of the chat.");
        }
        
        $this->model = Member::where('chat_id',$chatId)->get();
		return $this->sendResponse();
	}
    
    /**
     * @param Request $request
     * @param $chatId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $chatId)
	{
		$profileId = $request->user()->profile->id;
		
		//check ownership of chat.
		$chat = Chat::where('id',$chatId)->where('profile_id',$profileId)->first();
		if(!$chat){
		    return $this->sendError("Only chat owners can add members");
        }
        
        $profileIds = $request->input('profile_id');
		$data = [];
		$now = \Carbon\Carbon::now();
		foreach($profileIds as $profileId){
		    $data[] = ['chat_id'=>$chat->id,'profile_id'=>$profileId, 'created_at'=>$now->toDateTimeString()];
        }
		$this->model = Member::insert($data);

		return $this->sendResponse();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id Profile Id
	 * @return Response
	 */
	public function destroy(Request $request, $chatId, $id)
	{
        $profileId = $request->user()->profile->id;
        
        //check ownership of chat.
        $chat = Chat::where('id',$chatId)->where('profile_id',$profileId)->first();
        if(!$chat && $id != $profileId){
            return $this->sendError("Only chat owner can remove members");
        }
        
        $this->model = Member::where('chat_id',$chatId)->where('profile_id',$id)->delete();
    
        return $this->sendResponse();
	}
}
