<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Strategies\Paginator;
use App\V1\Chat\Member;
use App\V1\Chat;
use Carbon\Carbon;
use App\Http\Controllers\Api\Controller;
use Illuminate\Http\File;

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
       $query->where('profile_id',$profileId);
       })->leftJoin(\DB::raw('(SELECT chat_id, MAX(sent_on) as sent_on, recepient_id FROM message_recepients GROUP BY chat_id, recepient_id)
       message_recepients'),function($join) use ($profileId){
       $join->on('chats.id','=','message_recepients.chat_id')->where('message_recepients.recepient_id',$profileId);
       })->skip($skip)->take($take)->orderBy('message_recepients.sent_on', 'desc')->get();

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
                    $inputs['preview'] = json_decode($preview,true);
                    if(isset($preview['image']) && !empty($preview['image'])){
                        $image = $this->getExternalImage($preview['image'],$ownerProfileId);
                        $s3 = \Storage::disk('s3');
                        $filePath = 'p/' . $ownerProfileId . "/ci";
                        $resp = $s3->putFile($filePath, new File(storage_path($image)), 'public');
                        $ext= pathinfo($resp);
                        $ext = isset($ext['extension']) ? $ext['extension'] : null;
                        if($resp && ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png')){
                            $preview['image'] = \Storage::disk('s3')->url($resp);
                        }
                        else
                        {
                            $preview['image'] = null;
                        }
                        if($resp)
                        {
                            \File::delete(storage_path($image));
                        }

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
                    $fileMeta = $request->input('file_meta');
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
    	$checkAdmin = Member::withTrashed()->where('chat_id',$id)->where('profile_id',$loggedInProfileId)
            ->where('is_admin',1)->whereNull('exited_on')->exists();

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
                $this->model = \App\V1\Chat::where('id',$id)->update(['name'=>$request->input('name')]);
            }
            //$profileIds = Member::where('chat_id',$id)->get()->pluck('profile_id');

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
         $this->model == null ? $this->sendError('No existing chats with this user'): null;
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
        $chatIds = $request->input('chatId');
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
                    $message = \App\V1\Chat\Message::create(['message'=>$inputs['message'], 'profile_id'=>$loggedInProfileId, 'preview'=>$info['preview'], 'chat_id'=>$chat->id]);
                    $this->model = true;
                }
                if(count($chatIds))
                {
                    foreach ($chatIds as $chatId){
                        $isMember = Member::withTrashed()->where('chat_id',$chatId)->where('profile_id',$loggedInProfileId)->whereNull('exited_on')->exists();
                        if($isMember)
                        {
                            $message = \App\Chat\Message::create(['message'=>$inputs['message'], 'profile_id'=>$loggedInProfileId, 'preview'=>$info['preview'], 'chat_id'=>$chatId]);
                            $this->model = true;
                        }
                    }
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
        $this->model->update(['image'=>$file_url]);


    }

    // public function getChat(Request $request,$profileId)
    // {   
    //     $loggedInProfileId = $request->user()->profile->id;
    //     $this->model = Chat::open($loggedInProfileId,$profileId);
    //     if($this->model != null)
    //     {
    //         $chatId = $this->model->id;
    //         $page = $request->input('page');
    //         list($skip,$take) = Paginator::paginate($page);
    //         $this->model = \App\Chat\Message::join('message_recepients','chat_messages.id','=','message_recepients.message_id')
    //             ->where('chat_messages.chat_id',$chatId)->whereNull('message_recepients.deleted_on')
    //             ->where('message_recepients.recepient_id',$loggedInProfileId)->orderBy('message_recepients.sent_on','desc')->skip($skip)->take($take)->get();
    //             return $this->sendResponse();
    //     }
    //     return $this->sendResponse();
        
    // }

    public function rooms(Request $request)
    {
        $profileId = $request->user()->profile->id;
        $this->model = \DB::table('chats')->select('chats.id')
            ->join('chat_members','chat_members.chat_id','=','chats.id')
            ->where('chat_members.profile_id','=',$profileId)->whereNull('chat_members.exited_on')->get();
        return $this->sendResponse();
    }

    public function chatInfo(Request $request, $chatId)
    {
        $this->model = Chat::where('id',$chatId)->first();
        if($this->model ==null && empty($this->model))
        {
            $this->model = null;
            return $this->sendResponse();
        }
        if($this->model->chat_type == 0)
            $this->model->profiles = null;

        return $this->sendResponse();
    }

    public function disconnect(Request $request)
    {
        $profileId = $request->user()->profile->id;
        $status = $request->input('status');
        if($status == 1){
            \Log::info("this user is online".$profileId);
            $this->model = \Redis::sAdd("online:profile:", $profileId);
        }
        if($status == 0){
            $this->model = \Redis::sRem("online:profile:", $profileId);
            \Log::info("this user is offline".$profileId);
        }
        return $this->sendResponse();
    }

    public function chatGroup (Request $request)
    {
        $profileId = $request->user()->profile->id;

        $this->model = Chat::select('chats.*')->join('chat_members','chat_members.chat_id','=','chats.id')
            ->where('chat_members.profile_id','=',$profileId)->whereNotNull('chats.name')
            ->whereNull('chat_members.exited_on')->groupBy('chats.id')->where('chats.chat_type',0)->get();

        return $this->sendResponse();

    }
    public function featureMessage(Request $request,$feature,$featureId)
    {   
        $model = $this->getModel($feature,$featureId);
        if(empty($model))
        {
            return $this->sendError("Invalid model name or Id");
        }
        $inputs = $request->except(['_method','_token']);
        $inputs['is_mailable'] = $request->has('is_mailable') ? $request->input('is_mailable') : 0;
        $profileIds = isset($inputs['profile_id']) ? $inputs['profile_id'] : $this->sendError("Profile id cannot be null");
        if(!is_array($profileIds))
        {
            $profileIds = [$profileIds];
        }
        $LoggedInUser = $request->user();
        $loggedInProfileId = $LoggedInUser->profile->id;
        $data = [];

        if(isset($model->company_id)&& (!is_null($model->company_id)))
        {
            $checkUser = CompanyUser::where('company_id',$model->company_id)->where('profile_id',$loggedInProfileId)->exists();
            if(!$checkUser){
                return $this->sendError("Invalid Collaboration Project.");
            }
        }
        else if($model->profile_id != $loggedInProfileId){
            return $this->sendError("Invalid Collaboration Project.");
        }
        if($request->has('batch_id'))
        {
            $profileIds = \DB::table('collaborate_batches_assign')->where('batch_id',$request->input('batch_id'))
                ->get()->pluck('profile_id')->unique();
        }
        if($request->has('only_shortlisted'))
        {
            $profileIds = \DB::table('collaborate_applicants')->where('collaborate_id',$featureId)->whereNull('rejected_at')
                ->get()->pluck('profile_id')->unique();
        }
        if($request->has('only_rejected'))
        {
            $profileIds = \DB::table('collaborate_batches_assign')->where('collaborate_id',$featureId)->whereNotNull('rejected_at')
                ->get()->pluck('profile_id')->unique();
        }
        $data['userInfo'] = \DB::table('users')->leftjoin('profiles','users.id','=','profiles.user_id')->whereIn('profiles.id',$profileIds)->get();
        $data['message'] = $inputs['message'];
        $data['username'] = $LoggedInUser->name;
        $data['sender_info'] = $LoggedInUser;
        $data['model_title'] = $model->title;
        $data['model_name'] = $feature;
        $data['model_id'] = $model->id;
        event(new \App\Events\FeatureMailEvent($data,$profileIds,$inputs));
        $this->model = true;
        return $this->sendResponse();
        

    }
    private function getModel($feature,$featureId)
    {
        if($feature == 'jobs' || $feature == 'Jobs' || $feature == 'job' || $feature == 'Job')
            $feature = 'Job';
        else if($feature == 'collaborates' || $feature == 'Collaborates' || $feature == 'collaborate' || $feature == 'Collaborate')
            $feature = 'Collaborate';
        $class = "\\App\\" . ucwords($feature);
        return $class::find($featureId);
    }

}
