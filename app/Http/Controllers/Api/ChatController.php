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
        
        $this->model = Chat::withTrashed()->join('chat_profiles','chat_profiles.chat_id','=','chats.id')->where('chat_profiles.profile_id',$profileId)
            ->orderByRaw('chats.updated_at desc, chats.created_at desc')->skip($skip)->take($take)->get();
        
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
		$inputs = $request->except(['_method','_token','isSingle']);

		//set profile_id to logged in user automatically.
        //all profileIds passed in request would be added to Chat\Member;
		$profileIds = $inputs['profile_id'];
		if(!is_array($profileIds)){
		    $profileIds = [$profileIds];
        }
        $loggedInProfileId = $request->user()->profile->id;
		$inputs['profile_id'] = $loggedInProfileId;
		//check for existing chats only for single profileId.
		if(is_array($profileIds) && count($profileIds) === 1 && $request->input('isSingle') == 1){
            $existingChats = Chat::open($profileIds[0],$loggedInProfileId);
            if(!is_null($existingChats) && $existingChats->count() > 0){
                $this->messages[] = "chat_open";
                $this->model = $existingChats;
                return $this->sendResponse();
            }
        }
        unset($inputs['image']);
		$this->model = $this->model->create($inputs);
  
		if($request->hasFile("image")){
            $imageName = str_random("32") . ".jpg";
            $path = Chat::getImagePath($this->model->id);
            $response = $request->file('image')->storeAs($path,$imageName,['visibility'=>'public']);
            if(!$response){
                throw new \Exception("Could not save image " . $imageName . " at " . $path);
            }
            $this->model->update(['image'=>$response]);
        }

        //add members to the chat
        $now = \Carbon\Carbon::now()->toDateTimeString();
		$data = [];
		$chatId = $this->model->id;
        $chatProfileIds = [];
		//for add login profile id in member model
        $data[] = ['chat_id'=>$chatId,'profile_id'=>$loggedInProfileId, 'created_at'=>$now,'is_admin'=>1,'is_single'=>$request->input('isSingle')];
        $chatProfileIds[] = ['chat_id'=>$chatId,'profile_id'=>$loggedInProfileId, 'created_at'=>$now];
        foreach($profileIds as $profileId){
            $data[] = ['chat_id'=>$chatId,'profile_id'=>$profileId, 'created_at'=>$now,'is_admin'=>0,'is_single'=>$request->input('isSingle')];
            $chatProfileIds[] = ['chat_id'=>$chatId,'profile_id'=>$profileId, 'created_at'=>$now];
        }
        \DB::table('chat_profiles')->insert($chatProfileIds);
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
        
        //current user should be part of the chat, is a sufficient condition.
        $this->model = Chat::join('chat_profiles','chat_profiles.chat_id','=','chats.id')->where('chats.id',$id)->where('chat_profiles.profile_id',$profileId)->get();

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
        
        if($request->hasFile("image")){
            $imageName = str_random("32") . ".jpg";
            $path = Chat::getImagePath($chat->id);
            $response = $request->file('image')->storeAs($path,$imageName,['visibility'=>'public']);
            if(!$response){
                throw new \Exception("Could not save image " . $imageName . " at " . $path);
            }
            $inputs['image'] = $response;
        }
        unset($inputs['profile_id']);
        $this->model = $chat->update($inputs);

        //add members to the chat
        $profileIds = $request->input('profile_id');
        $now = \Carbon\Carbon::now()->toDateTimeString();
        $data = [];
        if(count($profileIds)) {
            foreach ($profileIds as $profileId) {
                \Log::info($profileId);
                $data[] = ['chat_id' => $id, 'profile_id' => $profileId, 'created_at' => $now,'is_admin'=>0];
            }
            $chat->members()->insert($data);
        }
		return $this->sendResponse();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request)
	{
	    $chadIds = $request->input('chat_id');
	    $loggedInProfileId = $request->user()->profile->id;
        $this->model = \DB::table('chat_profiles')->whereIn('chat_id',$chadIds)->where('profile_id',$loggedInProfileId)->delete();

        return $this->sendResponse();
	}
    
    public function rooms(Request $request)
    {
        $profileId = $request->user()->profile->id;
        $this->model = \DB::table('chat_profiles')->select('chat_profiles.chat_id')
            ->join('chat_members','chat_members.chat_id','=','chat_profiles.chat_id')
            ->where('chat_members.profile_id','=',$profileId)->get();
        return $this->sendResponse();
	}
 
}
