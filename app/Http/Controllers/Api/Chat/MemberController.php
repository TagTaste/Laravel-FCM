<?php

namespace App\Http\Controllers\Api\Chat;

use App\Chat;
use App\Chat\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

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
	public function index(Request $request, $id)
	{
	    $profileId = $request->user()->profile->id;
	    
	    //check if profileId is member of given chat $id
		$memberOfChat = Member::where('chat_id',$id)->where('profile_id',$profileId)->first();
  
		if(!$memberOfChat){
		    return $this->sendError("Profile is not part of the chat.");
        }
        
        $this->model = Member::where('chat_id',$id)->get();
		return $this->sendResponse();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request, $id)
	{
		$profileId = $request->user()->profile->id;
		
		//check ownership of chat.
		$chat = Chat::where('id',$id)->where('profile_id',$profileId)->first();
		if(!$chat){
		    return $this->sendError("Only chat owners can add members");
        }
        
        $profileIds = $request->input('profiles');
		$data = [];
		$now = \Carbon\Carbon::now();
		foreach($profileIds as $profileId){
		    $data[] = ['chat_id'=>$chat->id,'profile_id'=>$profileId, 'created_at'=>$now->toDateTimeString()];
        }
		$this->model = Member::insert($data);

		return $this->sendResponse();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$chat_member = $this->model->findOrFail($id);
		
		return view('chat_members.show', compact('chat_member'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$chat_member = $this->model->findOrFail($id);
		
		return view('chat_members.edit', compact('chat_member'));
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

		$chat_member = $this->model->findOrFail($id);		
		$chat_member->update($inputs);

		return redirect()->route('chat_members.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->model->destroy($id);

		return redirect()->route('chat_members.index')->with('message', 'Item deleted successfully.');
	}
}