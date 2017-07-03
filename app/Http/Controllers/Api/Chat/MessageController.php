<?php

namespace App\Http\Controllers\Api\Chat;

use App\Chat;
use App\Chat\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class MessageController extends Controller
{
	/**
	 * Variable to model
	 *
	 * @var message
	 */
	protected $model;

	/**
	 * Create instance of controller with Model
	 *
	 * @return void
	 */
	public function __construct(Message $model)
	{
		$this->model = $model;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request,$chatId)
	{
	    $profileId = $request->user()->profile->id;
        //check ownership
        
        $memberOfChat = Chat\Member::where('chat_id',$chatId)->where('profile_id',$profileId)->exists();
        
        if(!$memberOfChat) {
            return $this->sendError("You are not part of this chat.");
        }
        
		$this->model = $this->model->where('chat_id',$chatId)->paginate();

		return $this->sendResponse();
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request, $chatId)
	{
		$inputs = $request->all();
        
        $profileId = $request->user()->profile->id;
        //check ownership
        
        $memberOfChat = Chat\Member::where('chat_id',$chatId)->where('profile_id',$profileId)->exists();
        
        if(!$memberOfChat) {
            return $this->sendError("You are not part of this chat.");
        }
        
        $inputs['chat_id'] = $chatId;
        $inputs['profile_id'] = $profileId;
		$this->model = $this->model->create($inputs);

		return $this->sendResponse();
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$message = $this->model->findOrFail($id);
		
		return view('messages.edit', compact('message'));
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

		$message = $this->model->findOrFail($id);		
		$message->update($inputs);

		return redirect()->route('messages.index')->with('message', 'Item updated successfully.');
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

		return redirect()->route('messages.index')->with('message', 'Item deleted successfully.');
	}
}