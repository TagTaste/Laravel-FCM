<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Strategies\Paginator;
use App\Chat\Member;
use App\Chat;
use Carbon\Carbon;
use App\Http\Controllers\Api\Controller;

class ChatController extends Controller
{
    //
    public $model;
    public $now;

    public function __construct(Chat $model)
    {
        $this->now = \Carbon\Carbon::now()->toDateTimeString();
        $this->model = $model;
    }

    public function index(Request $request)
    {
    	$profileId = $request->user()->id;
        
        $this->model = Chat::whereHas('members',function($query) use ($profileId) {
        $query->where('profile_id',$profileId)->withTrashed();
        })->leftJoin(\DB::raw('(SELECT chat_id, MAX(sent_on) as sent_on, recepient_id, deleted FROM message_recepients GROUP BY chat_id, recepient_id, deleted)
        message_recepients'),function($join) use ($profileId){
        $join->on('chats.id','=','message_recepients.chat_id')->where('message_recepients.recepient_id',$profileId);
        })->orderBy('message_recepients.sent_on', 'desc')->where('message_recepients.deleted',0)->get();

        return $this->sendResponse();
    }

    public function store(Request $request)
    {
    	$ownerProfileId = $request->user()->profile->id;
            //String[] $profileIds = request.getParameterValues("profileId");
    		$profileIds = $request->input('profileId');
    		if(!is_array($profileIds))
    		{
            	$profileIds = [$profileIds];
        	}
        	if(!in_array($ownerProfileId, $profileIds))
        	{
                 $profileIds[] = $ownerProfileId;
        	}

            $inputs['chat_type'] = $request->input('chat_type');
            $inputs['name']= $request->input('name') == null ? null: $request->input('name');
            $inputs['image']= $request->input('image') == null ? null: $request->input('image');
            $inputs['profile_id']=$ownerProfileId;
            $inputs['created_at']=$this->now; 

  	  	if($request->input('chat_type') == '1' && count($profileIds) === 2)
    	{  
    		$existingChats = Chat::open($ownerProfileId,$profileIds[0]);

    		if($existingChats === null)
    		{
    			$this->createChatRoom($inputs,$profileIds);
                return $this->sendResponse();
    		}
    		else
    		{
    			$this->model = $existingChats;
    			return $this->sendResponse();
    		}
    	}
    	elseif($request->input('chat_type') == '0')
    	{
    		if($request->input('name') == null)
    		{
    			return $this->sendError('Name field cannot be empty');
    		}
    		else
    		{
                $this->createChatRoom($inputs, $profileIds);
                if($request->hasFile("image") && $request->input('chat_type')==0){
                    $this->uploadImage($request);
                }
                return $this->sendResponse();
    		}
    	}
    	else
    	{
    		return $this->sendError("Chat type is not defined");
    	}
    }

    public function createChatRoom($inputs, $profileIds)
    {  
    	$this->model = Chat::create($inputs);
        $chatId = $this->model->id;
    	$input = [];
    	foreach ($profileIds as $profileId)
    	 {
    		if($profileId == $inputs['profile_id'])
    			$isAdmin = 1;
    		else
    			$isAdmin = 0;
    		$input[] = ['chat_id'=>$chatId,'profile_id'=>$profileId,'created_at'=>$this->now,'is_admin'=>$isAdmin];
    	}
    	
    	$this->model->members()->insert($input);
        $info['chatId'] = $chatId;
        $info['content'] = null;
        $info['type'] = 1;
        event(new \App\Events\Chat\MessageTypeEvent($info,$inputs['profile_id']));
    }

    public function uploadImage($request)
    {	
        $imageName = str_random("32") . ".jpg";
        $path = Chat::getImagePath($this->model->id);
        $response = $request->file('image')->storeAs($path,$imageName,['visibility'=>'public']);
        if(!$response){
            throw new \Exception("Could not save image " . $imageName . " at " . $path);
        }
        $this->model->update(['image'=>$response]);
  
    }

    public function show(Request $request,$id)
    {	$profileId = $request->user()->profile->id;

    	$this->model = Chat::whereHas('members',function($query) use ($profileId){
    		$query->where('profile_id',$profileId)->withTrashed();
    	})->join(\DB::raw('(SELECT chat_id, MAX(sent_on) as sent_on, recepient_id, deleted FROM message_recepients GROUP BY chat_id, recepient_id, deleted)
        message_recepients'),function($join) use ($profileId){
        $join->on('chats.id','=','message_recepients.chat_id')->where('message_recepients.recepient_id',$profileId);
        })->where('chats.id',$id)->where('message_recepients.deleted',0)->first();
    	$this->model == null ? $this->sendError('This chat id doesnt belong to this user') : null;
        return $this->sendResponse();

    }

    public function update(Request $request, $id)
    {
    	$profileId = $request->user()->profile->id;

    	$this->model = Chat::whereHas('members',function($query) use ($profileId){
    		$query->where('profile_id',$profileId);
    	})->where('id',$id)->where('chat_type',0)->first();
    	if($this->model == null)
        {
            return $this->sendError("This chat doesnt belong to this user");
        }
    	if($request->hasFile("image")){
            $this->uploadImage($request);
            $info['chatId'] = $id;
            $info['content'] = null;
            $info['type'] = 4;
            event(new \App\Events\Chat\MessageTypeEvent($info,$request->user()->profile));
        }
        if($request->input('name')!= null)
        {
        	$this->model->update(['name'=>$request->input('name')]);
            $info['chatId'] = $id;
            $info['content'] = $request->input('name');
            $info['type'] = 5;
            event(new \App\Events\Chat\MessageTypeEvent($info,$request->user()->profile));
        }
        return $this->model;
    }

    public function chatSearch(Request $request)
    {
    	$key = $request->input('k');
    	 $loggedInProfileId = $request->user()->profile->id;
    	$data['groups'] = Chat::whereHas('members',function($query) use ($loggedInProfileId){
    		$query->where('profile_id',$loggedInProfileId);
    	})->where('name','like','%'.$key.'%')->where('chat_type',0)->get();
    	$profileIds = \Redis::SMEMBERS("followers:profile:".$loggedInProfileId);
    	$data['profile'] = \App\Recipe\Profile::whereIn('profiles.id',$profileIds)->join('users','profiles.user_id','users.id')->where('users.name','like','%'.$key.'%')->get();
        $this->model = $data;
    	return $this->sendResponse();
    	
	
    }

    public function getChatId(Request $request)
    {
    	$loggedInProfileId = $request->user()->profile->id;
    	$profileId = $request->input('profileId');
        if($profileId == $loggedInProfileId || !\App\Profile::where('id',$profileId)->exists())
        {
            return $this->sendError("Invalid Input");
        }
    	$this->model = Chat::open($loggedInProfileId,$profileId);
         $this->model == null ? $this->sendError('No existing chats with this user') : null;
         return $this->sendResponse();
    }

    public function shareAsMessage(Request $request)
    {
        // $profileIds = $request->input('profileId');
        // $chatIds = $request->input('chatId');
        // $inputs = $request->all();

        // event(new \App\Events\Chat\ShareMessage($chatIds,$profileIds,$inputs,$request->user()));

        // $this->model = true;
        $loggedInProfileId = $request->user()->profile->id;

        $profileIds = $request->input('profileId');
        $inputs = $request->all();

        if(isset($inputs['preview']['image']) && !empty($inputs['preview']['image'])){
                    $image = $this->getExternalImage($inputs['preview']['image'],$loggedInProfileId);
                    $s3 = \Storage::disk('s3');
                    $filePath = 'p/' . $loggedInProfileId . "/ci";
                    $resp = $s3->putFile($filePath, new File(storage_path($image)), 'public');
                    $inputs['preview']['image'] = $resp;
                }
                if(isset($inputs['preview']))
                {
                    $info['preview'] = json_encode($inputs['preview']);
                }
                else
                {
                    $info['preview'] = null;
                }
                foreach ($profileIds as $profileId) {
                    $chat = Chat::open($loggedInProfileId, $profileId);
                    if (!$chat) {
                        $chat = Chat::create(['profile_id'=>$loggedInProfileId, 'chat_type'=>1]);
                        $input = [];
                        $input[] = ['chat_id'=>$chat->id, 'profile_id'=>$loggedInProfileId, 'is_admin'=>1];
                        $input[] = ['chat_id'=>$chat->id, 'profile_id'=>$profileId, 'is_admin'=>0]; 
                        $member = Member::insert($input);
                    }
                    $message = \App\Chat\Message::create(['message'=>$inputs['message'], 'profile_id'=>$loggedInProfileId, 'preview'=>$info['preview'], 'chat_id'=>$chat->id]);
                    $recepients = [];
                    $recepients[] = ['recepient_id'=>$loggedInProfileId, 'message_id'=>$message->id, 'chat_id'=>$chat->id, 'read_on'=>$this->now];
                    $recepients[] = ['recepient_id'=>$profileId, 'message_id'=>$message->id, 'chat_id'=>$chat->id, 'read_on'=>null];
                    \DB::table('message_recepients')->insert($recepients);
                    $this->model = $message;
                }
        return $this->sendResponse();
    }

    public function getExternalImage($url,$profileId){
        $path = 'images/p/' . $profileId . "/cimages/";
        \Storage::disk('local')->makeDirectory($path);
        $filename = str_random(10) . ".image";
        $saveto = storage_path("app/" . $path) .  $filename;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        $raw=curl_exec($ch);
        curl_close ($ch);

        $fp = fopen($saveto,'a');
        fwrite($fp, $raw);
        fclose($fp);
        return "app/" . $path . $filename;
    }

}
