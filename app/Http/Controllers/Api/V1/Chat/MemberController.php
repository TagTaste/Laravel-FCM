<?php

namespace App\Http\Controllers\Api\V1\Chat;

use Illuminate\Http\Request;
use App\Strategies\Paginator;
use App\Chat\Member;
use App\Chat;
use Carbon\Carbon;
use App\Http\Controllers\Api\Controller;

class MemberController extends Controller
{
    //
    public $model;
    public $time;

    public function __construct(Member $model)
    {
        $this->model = $model;
        $this->time = \Carbon\Carbon::now()->toDateTimeString();
    }

    public function index(Request $request,$chatId)
    {
    	$loggedInProfileId = $request->user()->profile->id;

    	$member = \DB::table('chat_members')->where('chat_id',$chatId)->where('profile_id',$loggedInProfileId)->first();

        if(!isset($member) || is_null($member))
        {
            return $this->sendError("This user doesnt belong to this chat");
        }

        if(is_null($member->deleted_at))
        {
            $this->model = Member::where('chat_id',$chatId)->get();
        }
        else
        {
            $this->model = Member::where('chat_id',$chatId)->where('created_at','<=',$members->deleted_at)->get();
        }
    	return $this->sendResponse();
    }

    public function store(Request $request,$chatId)
    {
    	$loggedInProfileId = $request->user()->profile->id;
    	if(!$this->isAdmin($chatId,$loggedInProfileId))
    	{
    		return $this->sendError('Only group admins can perform this action');
    	}
    	$profileIds = $request->input('profileId');
    	if(!is_array($profileIds))
    	{
    	    $profileIds = [$profileIds];
    	}

    	Member::where('chat_id',$chatId)->whereIn('profile_id',$profileIds)->update(['deleted_at'=>null]);

    	$members = Member::where('chat_id',$chatId)->pluck('profile_id');
    	$members = [$members];
    	$profileIds = array_diff($profileIds,$members);
        $chatMembers = [];

        foreach ($profileIds as $profileId)
        {
            $chatMembers[] = ['chat_id'=>$chatId,'profile_id'=>$profileId,'created_at'=>$this->time,'is_admin'=>0];
        }

        $this->model = $this->model->insert($chatMembers);

        $messageInfo = ['chat_id'=>$chatId,'profile_id'=>$loggedInProfileId,'type'=>2];

        $model=\App\Chat\Message::create($messageInfo);
        $messageRecepients = [];
        foreach ($members as $profileId)
        {
            $messageRecepients = ['message_id'=>$model->id, 'recepient_id'=>$loggedInProfileId,'sender_id'=>$profileId, 'chat_id'=>$chatId];
        }
        \DB::table('chat_message_recepients')->insert($messageRecepients);

        return $this->sendResponse();
    }

    public function destroy(Request $request, $chatId, $profileId)
    {
    	if($this->getChatType($chatId) == 1)
    	{
    		return $this->sendError("invalid Function only valid on group chats");
    	}
    	$loggedInProfileId = $request->user()->profile->id;
    	if(!$this->isAdmin($chatId,$loggedInProfileId) && $loggedInProfileId != $profileId)
    	{
    		return $this->sendError("This user cant perform this action");
    	}
    	$checkSuperAdmin = \Db::table('chats')->where('id',$chatId)->first();
    	if($checkSuperAdmin->profile_id == $profileId)
    	{
    		return $this->sendError('Super admin cannot be removed from the group');
    	}
    	$this->model = Member::where('chat_id',$chatId)->where('profile_id',$profileId)->delete();

    	$type = $loggedInProfileId == $profileId ? 4 : 3;
        $messageInfo = ['chat_id'=>$chatId,'profile_id'=>$loggedInProfileId,'type'=>$type];

        $model=\App\Chat\Message::create($messageInfo);
        $messageRecepients = [];
        $profileIds = Member::where('chat_id',$chatId)->pluck('profile_id');
        foreach ($profileIds as $profileId)
        {
            $messageRecepients = ['message_id'=>$model->id, 'recepient_id'=>$loggedInProfileId,'sender_id'=>$profileId, 'chat_id'=>$chatId];
        }
        \DB::table('chat_message_recepients')->insert($messageRecepients);

    	return $this->sendResponse();

    }

    public function addAdmin(Request $request, $chatId)
    { 
    	$loggedInProfileId = $request->user()->profile->id;
    	if(!$this->isAdmin($chatId, $loggedInProfileId))
    	{
    		return $this->sendError('This user cannot perform this action');
    	}
    	$profileIds = $request->input('profileId');
    	if(!is_array($profileIds))
    	{
           	$profileIds = [$profileIds];
        }
        $this->model = $this->model->where('chat_id',$chatId)->whereIn('profile_id',$profileIds)->update(['is_admin'=>1]);


        $type = 7 ;

        $messageInfo = ['chat_id'=>$chatId,'profile_id'=>$loggedInProfileId,'type'=>$type];

        $model=\App\Chat\Message::create($messageInfo);
        $messageRecepients = [];
        $profileIds = Member::where('chat_id',$chatId)->pluck('profile_id');
        foreach ($profileIds as $profileId)
        {
            $messageRecepients = ['message_id'=>$model->id, 'recepient_id'=>$loggedInProfileId,'sender_id'=>$profileId, 'chat_id'=>$chatId];
        }
        \DB::table('chat_message_recepients')->insert($messageRecepients);

        return $this->sendResponse();
    }
    public function removeAdmin(Request $request, $chatId)
    {
    	$loggedInProfileId = $request->user()->profile->id;
    	if (!$this->isAdmin($chatId, $loggedInProfileId)) {
    		return $this->sendError('This user cant perform this action');
    	}
    	$profileIds = $request->input('profileId');
    	if(!is_array($profileIds))
    	{
           	$profileIds = [$profileIds];
        } 
        if(in_array(Chat::where('id',$chatId)->pluck('profile_id'), $profileIds))
        {
            return $this->sendError("Super admin cannot be removed from the admin");
        }
    	$this->model = $this->model->where('chat_id',$chatId)->whereIn('profile_id',$profileIds)->update(['is_admin'=>0]);

        $type = 8 ;
        $messageInfo = ['chat_id'=>$chatId,'profile_id'=>$loggedInProfileId,'type'=>$type];

        $model=\App\Chat\Message::create($messageInfo);
        $messageRecepients = [];
        $profileIds = Member::where('chat_id',$chatId)->pluck('profile_id');
        foreach ($profileIds as $profileId)
        {
            $messageRecepients = ['message_id'=>$model->id, 'recepient_id'=>$loggedInProfileId,'sender_id'=>$profileId, 'chat_id'=>$chatId];
        }
        \DB::table('chat_message_recepients')->insert($messageRecepients);

        return $this->sendResponse();
    }
    protected function getChatType($id)
    {
    	return Chat::where('id',$id)->pluck('chat_type')->first(); 
    }

    protected function isAdmin($chatId,$profileId)
    {
    	return Member::where('profile_id',$profileId)->where('chat_id',$chatId)->where('is_admin',1)->exists();
    }

    protected function isMember($profileId, $chatId)
    {
        return \DB::table('chat_members')->where('chat_id',$chatId)->where('profile_id',$profileId)->first();
    }
}
