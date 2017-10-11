<?php

namespace App\Http\Controllers\Api\Chat;

use App\Chat;
use App\Chat\Message;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\API\Chat\Message\StoreRequest;
use App\Strategies\Paginator;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
	public function index(StoreRequest $request,$chatId)
	{
	    $profileId = $request->user()->profile->id;
        //check ownership
        
        $memberOfChat = Chat\Member::where('chat_id',$chatId)->where('profile_id',$profileId)->exists();
        
        if(!$memberOfChat) {
            return $this->sendError("You are not part of this chat.");
        }
        
        $page = $request->input('page');
        list($skip,$take) = Paginator::paginate($page);
        
		$this->model = $this->model->where('chat_id',$chatId)->orderBy('created_at','desc')->skip($skip)->take($take)->get();

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
	    \Log::info($request->hasFile('file'));
		$inputs = $request->except(['file']);
        $profileId = $request->user()->profile->id;
        //check ownership
        
        $memberOfChat = Chat\Member::where('chat_id',$chatId)->where('profile_id',$profileId)->exists();
        
        if(!$memberOfChat) {
            return $this->sendError("You are not part of this chat.");
        }
        if($request->hasFile("file"))
        {
            $path = "profile/$profileId/chat/$chatId/file";
            $filename = $request->file('file')->getClientOriginalName();
    
            $inputs['file'] = $request->file("file")->storeAs($path, $filename,['visibility'=>'public']);
        }
        $inputs['chat_id'] = $chatId;
        $inputs['profile_id'] = $profileId;
		$this->model = $this->model->create($inputs);

		event(new \App\Events\Chat\Message($this->model,$request->user()->profile));
		return $this->sendResponse();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $chatId, $id)
	{
        $loggedInProfileId = $request->user()->profile->id;
        //check ownership
        
        $memberOfChat = Chat\Member::where('chat_id',$chatId)->where('profile_id',$loggedInProfileId)->exists();
        
        if(!$memberOfChat) {
            return $this->sendError("You are not part of this chat.");
        }
        
        $profileId = $request->input('profile_id');
        $this->model = $this->model->where("chat_id",$chatId)
            ->where('id',$id)->where(function($query) use ($profileId,$loggedInProfileId){
                $query->where('profile_id','=',$profileId)->orWhere('profile_id','=',$loggedInProfileId);
            })->destroy();

		return redirect()->route('messages.index')->with('message', 'Item deleted successfully.');
	}
    
    public function markRead(Request $request, $chatId, $id)
    {
        $loggedInProfileId = $request->user()->profile->id;
        //check ownership
    
        $memberOfChat = Chat\Member::where('chat_id',$chatId)->where('profile_id',$loggedInProfileId)->exists();
    
        if(!$memberOfChat) {
            return $this->sendError("You are not part of this chat.");
        }
        
        $now = Carbon::now()->toDateTimeString();
        
        $this->model = $this->model->where('chat_id',$chatId)->where('id',$id)->update(['read_on'=>$now]);
        return $this->sendResponse();
	}
}