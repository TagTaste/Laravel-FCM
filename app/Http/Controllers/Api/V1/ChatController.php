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
        $profileId = $request->user()->profile->id;

        $page = $request->input('page');
        list($skip,$take) = Paginator::paginate($page);
       $this->model = Chat::whereHas('members',function($query) use ($profileId) {
       $query->where('profile_id',$profileId)->withTrashed();
       })->leftJoin(\DB::raw('(SELECT chat_id, MAX(sent_on) as sent_on, recepient_id, deleted FROM message_recepients GROUP BY chat_id, recepient_id, deleted)
       message_recepients'),function($join) use ($profileId){
       $join->on('chats.id','=','message_recepients.chat_id')->where('message_recepients.recepient_id',$profileId);
       })->skip($skip)->take($take)->orderBy('message_recepients.sent_on', 'desc')->where('message_recepients.deleted',0)->get();

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
        $inputs = [];
        $inputs['chat_type'] = $request->input('chat_type');
        $inputs['name']= $request->input('name') == null ? null: $request->input('name');
        $inputs['image']= $request->input('image') == null ? null: $request->input('image');
        $inputs['profile_id']=$ownerProfileId;
        $inputs['created_at']=$this->now;
        $inputs['signature'] = $request->input('signature');

  	  	if($request->input('chat_type') == 1 && count($profileIds) === 2)
    	{   $message = $request->input('message');
    		$existingChats = Chat::open($ownerProfileId,$profileIds[0]);

    		if($existingChats === null)
    		{
    		    if(!$request->has('message') && !$request->has('preview') && !$request->has('file'))
                {
                    return $this->sendError("Please enter message");
                }
    		    $chatId = $this->createChatRoom($inputs,$profileIds,$message);
    		    $preview = $request->input('preview');
                if(isset($preview) && !empty($preview))
                {
                    if(isset($preview['image']) && !empty($preview['image'])){
                        $image = $this->getExternalImage($preview['image'],$ownerProfileId);
                        $s3 = \Storage::disk('s3');
                        $filePath = 'p/' . $ownerProfileId . "/ci";
                        $resp = $s3->putFile($filePath, new File(storage_path($image)), 'public');
                        $preview['image'] = $resp;
                    }
                    $preview = json_encode($preview);
                }
                else
                {
                    $preview = null;
                }
                $file = null;
                $fileMeta = null;
                if($request->has('file') && is_null($request->input('file')))
                {
                    $file = $request->input('file');
                }
                if($request->has('file_meta') && is_null($request->input('file_meta')))
                {
                    $fileMeta = json_encode($request->input('file_meta'));
                }
                $messageInfo = ['profile_id'=>$ownerProfileId, 'chat_id'=>$chatId,
                    'message'=>$request->input('message'), 'parent_message_id'=>null,
                    'preview'=> $preview, 'signature'=>$request->input('signature'),'file'=>$file,
                    'file_meta'=>$fileMeta];
                event(new \App\Events\Chat\MessageTypeEvent($messageInfo));
                return $this->sendResponse();

    		}
    		else
    		{
                $this->model = $existingChats;
    			return $this->sendResponse();
    		}
    	}
    	else if($request->input('chat_type') == 0)
    	{
    		if($request->input('name') == null)
    		{
    			return $this->sendError('Name field cannot be empty');
    		}
    		else
    		{

                $chatId = $this->createChatRoom($inputs, $profileIds);
                if($request->has('image'))
                {
                    $this->uploadImage($request, $chatId);
                }
                return $this->sendResponse();
    		}
    	}
    	else
    	{
    		return $this->sendError("Chat type is not defined");
    	}
    }

    public function show(Request $request,$id)
    {
        $profileId = $request->user()->profile->id;

        $page = $request->input('page');
        list($skip,$take) = Paginator::paginate($page);
        $this->model = Chat::whereHas('members',function($query) use ($profileId) {
            $query->where('profile_id',$profileId)->withTrashed();
        })->where('id',$id)->first();

        return $this->sendResponse();
    }

    // only in change group name and group image and by only admin
    public function update(Request $request, $id)
    {
        $loggedInProfileId = $request->user()->profile->id;
    	$checkAdmin = Member::where('chat_id',$id)->where('profile_id',$loggedInProfileId)
            ->where('is_admin',1)->whereNull('deleted_at')->exists();

    	if(!isset($checkAdmin) || is_null($checkAdmin))
        {
            return $this->sendError("You are not a part of this chat.");
        }
        if($request->has('name') || $request->has('image'))
        {
            if($request->has('image'))
            {
                $this->model = Chat::where('id',$id)->first();
                $this->uploadImage($request, $id);
            }
            if($request->has('name'))
            {   
                $this->model = \App\Chat::where('id',$id)->update(['name'=>$request->input('name')]);
            }
            $profileIds = Member::where('chat_id',$id)->get()->pluck('profile_id');

            $type = $request->has('name') ? 5 : 6;
            $messageInfo = ['chat_id'=>$id,'profile_id'=>$loggedInProfileId,'type'=>$type, 'message'=>$loggedInProfileId.'.'.\DB::table('chat_message_type')->where('id',$type)->pluck('text')->first().'.'.null];
            event(new \App\Events\Chat\MessageTypeEvent($messageInfo));
            $this->model = Chat::where('id',$id)->first();
            return $this->sendResponse();
        }
        else
        {
            $this->model = false;
            return $this->sendResponse();
        }
    }

    public function chatSearch(Request $request)
    {
    	$key = $request->input('k');
    	 $loggedInProfileId = $request->user()->profile->id;
    	$data['groups'] = Chat::whereHas('members',function($query) use ($loggedInProfileId){
    		$query->where('profile_id',$loggedInProfileId)->withTrashed();
    	})->where('name','like','%'.$key.'%')->where('chat_type',0)->get();

    	$profileIds = \Redis::SMEMBERS("followers:profile:".$loggedInProfileId);
    	$data['profile'] = \App\Recipe\Profile::whereIn('profiles.id',$profileIds)->join('users','profiles.user_id','users.id')
            ->where('users.name','like','%'.$key.'%')->get();

        $this->model = $data;
    	return $this->sendResponse();
    	
	
    }

    public function getChatId(Request $request)
    {
    	$loggedInProfileId = $request->user()->profile->id;
    	$profileId = $request->input('profileId');
        if($profileId == $loggedInProfileId)
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


    public function createChatRoom($inputs, $profileIds, $message = null)
    {
        $this->model = Chat::create($inputs);
        $chatId = $this->model->id;
        $chatMembers = [];
        foreach ($profileIds as $profileId)
        {
            if($profileId == $inputs['profile_id'] && $this->model->chat_type == 0)
                $isAdmin = 1;
            else
                $isAdmin = 0;
            $chatMembers[] = ['chat_id'=>$chatId,'profile_id'=>$profileId,'created_at'=>$this->now,'is_admin'=>$isAdmin];
            $obj = ['chatId'=>$chatId, 'profileId'=>$profileId];
            $obj = json_encode($obj);
            \Redis::publish("new-chat",$obj);
        }

        $this->model->members()->insert($chatMembers);
        if($this->model->chat_type == 0)
        {
            $messageInfo = ['chat_id'=>$chatId,'profile_id'=>$inputs['profile_id'],'type'=>1, 'message'=>$inputs['profile_id'].'.'.\DB::table('chat_message_type')->where('id',1)->pluck('text')->first().'.'.null];
            event(new \App\Events\Chat\MessageTypeEvent($messageInfo));
        }
        return $chatId;
    }

    public function uploadImage($request,$chatId)
    {
        $imageName = str_random("32") . ".jpg";
        $path = Chat::getImagePath($chatId);
        $file = $request->file('image');
        $response = $file->storeAs($path,$imageName,['visibility'=>'public',"disk"=>"s3"]);
        $file_url = \Storage::disk('s3')->url($response);
        if(!$response){
            throw new \Exception("Could not save image " . $imageName . " at " . $path);
        }
        $this->model = $this->model->update(['image'=>$file_url]);


    }

    public function getChat(Request $request,$profileId)
    {   
        $loggedInProfileId = $request->user()->profile->id;
        $this->model = Chat::open($loggedInProfileId,$profileId);
        if($this->model != null)
        {
            $chatId = $this->model->id;
            $page = $request->input('page');
            list($skip,$take) = Paginator::paginate($page);
            $this->model = \App\Chat\Message::join('message_recepients','chat_messages.id','=','message_recepients.message_id')
                ->where('chat_messages.chat_id',$chatId)->whereNull('message_recepients.deleted_on')
                ->where('message_recepients.recepient_id',$loggedInProfileId)->orderBy('message_recepients.sent_on','desc')->skip($skip)->take($take)->get();
                return $this->sendResponse();
        }
        return $this->sendResponse();
        
    }

    public function rooms(Request $request)
    {
        $profileId = $request->user()->profile->id;
        $this->model = \DB::table('chats')->select('chats.id')
            ->join('chat_members','chat_members.chat_id','=','chats.id')
            ->where('chat_members.profile_id','=',$profileId)->whereNull('chat_members.deleted_at')->get();
            \Redis::sAdd("online", $profileId);
        return $this->sendResponse();
    }

    public function chatInfo(Request $request, $chatId)
    {
        $this->model = Chat::where('id',$chatId)->first();
        if($this->model->chat_type == 0)
            $this->model->profiles = null;

        return $this->sendResponse();
    }

    public function disconnect(Request $request)
    {
        $profileId = $request->user()->profile->id;
        $this->model = \Redis::sRem("online", $profileId);
        return $this->sendResponse();
    }

}
