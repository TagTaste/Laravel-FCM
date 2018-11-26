<?php

namespace App\Http\Controllers\Api\V1\Chat;

use Illuminate\Http\Request;
use App\Strategies\Paginator;
use App\V1\Chat\Member;
use App\V1\Chat;
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

    	$member = \DB::table('chat_members')->where('chat_id',$chatId)->where('profile_id',$loggedInProfileId)->whereNull('exited_on')->first();

        if(!isset($member) || is_null($member))
        {
            return $this->sendError("This user doesnt belong to this chat");
        }

        if(is_null($member->deleted_at))
        {
            $this->model = Member::where('chat_id',$chatId)->whereNull('exited_on')->get();
        }
        else
        {
            $this->model = Member::where('chat_id',$chatId)->where('created_at','<=',$member->deleted_at)->whereNull('exited_on')->get();
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
    	Member::withTrashed()->where('chat_id',$chatId)->whereIn('profile_id',$profileIds)->update(['exited_on'=>null]);

    	$memberIds = Member::where('chat_id',$chatId)->pluck('profile_id')->toArray();
        foreach ($profileIds as $profileId ) {
            $messageInfo = ['chat_id'=>$chatId,'profile_id'=>$loggedInProfileId,'type'=>2, 'message'=>$loggedInProfileId.'.'.\DB::table('chat_message_type')->where('id',2)->pluck('text')->first().'.'.$profileId];

            event(new \App\Events\Chat\MessageTypeEvent($messageInfo));
            $messageInfo = [];
        }
        $profileIds = array_diff($profileIds, $memberIds);
        $chatMembers = [];

        foreach ($profileIds as $profileId)
        {
            $chatMembers[] = ['chat_id'=>$chatId,'profile_id'=>$profileId,'created_at'=>$this->time,'is_admin'=>0];
        }

        $this->model = $this->model->insert($chatMembers);
        //$members = \App\Chat\Member::where('chat_id',$chatId)->pluck('profile_id');

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
    	$checkSuperAdmin = \DB::table('chats')->where('id',$chatId)->first();
    	if($checkSuperAdmin->profile_id == $profileId)
    	{
    		return $this->sendError('Super admin cannot be removed from the group');
    	}

        $type = $loggedInProfileId == $profileId ? 4 : 3;
        $messageInfo = ['chat_id'=>$chatId,'profile_id'=>$loggedInProfileId,'type'=>$type, 'message'=>$loggedInProfileId.'.'.\DB::table('chat_message_type')->where('id',$type)->pluck('text')->first().'.'.$profileId];
        event(new \App\Events\Chat\MessageTypeEvent($messageInfo));
    	$this->model = Member::where('chat_id',$chatId)->where('profile_id',$profileId)->update(['exited_on'=>$this->time]);

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
        $profileIdsExists = Member::whereIn('profile_id',$profileIds)->where('chat_id',$chatId)->whereNotNull('exited_on')->exists();
    	if($profileIdsExists)
        {
            return $this->sendError('This user cannot perform this action');
        }
        $checkAdmin = Member::whereIn('profile_id',$profileIds)->where('chat_id',$chatId)->where('is_admin',1)->whereNull('exited_on')->exists();
        if($checkAdmin)
        {
            return $this->sendError('This user already admin');
        }
        $this->model = $this->model->where('chat_id',$chatId)->whereIn('profile_id',$profileIds)->whereNull('exited_on')->update(['is_admin'=>1]);


        $type = 7 ;
        if (in_array($loggedInProfileId, $profileIds)) {
            return $this->sendError("user cannot make himself admin");
        }
        foreach ($profileIds as $profileId) {
            $messageInfo = ['chat_id'=>$chatId,'profile_id'=>$loggedInProfileId,'type'=>$type, 'message'=>$profileId.'.'.\DB::table('chat_message_type')->where('id',$type)->pluck('text')->first().'.'.$loggedInProfileId];
            event(new \App\Events\Chat\MessageTypeEvent($messageInfo));
        }

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
        $profileIds = array_diff($profileIds, Chat::where('id',$chatId)->pluck('profile_id')->toArray());
    	$this->model = $this->model->where('chat_id',$chatId)->whereNull('exited_on')->whereIn('profile_id',$profileIds)->update(['is_admin'=>0]);

        $type = 8 ;
        foreach ($profileIds as $profileId) {
            $messageInfo = ['chat_id'=>$chatId,'profile_id'=>$loggedInProfileId,'type'=>$type, 'message'=>$profileId.'.'.\DB::table('chat_message_type')->where('id',$type)->pluck('text')->first().'.'.$loggedInProfileId];
            event(new \App\Events\Chat\MessageTypeEvent($messageInfo));
        }

        return $this->sendResponse();
    }
    protected function getChatType($id)
    {
    	return Chat::where('id',$id)->pluck('chat_type')->first(); 
    }

    protected function isAdmin($chatId,$profileId)
    {
    	return Member::where('profile_id',$profileId)->where('chat_id',$chatId)->where('is_admin',1)->whereNull('exited_on')->exists();
    }

    protected function isMember($profileId, $chatId)
    {
        return \DB::table('chat_members')->where('chat_id',$chatId)->where('profile_id',$profileId)->whereNull('exited_on')->first();
    }

    public function getMembersToAdd(Request $request, $chatId)
    {
        $chatProfileIds = \DB::table('chat_members')->where('chat_id',$chatId)->whereNull('exited_on')->get()->pluck('profile_id');
        $loggedInProfileId = $request->user()->profile->id ;
        $this->model = [];
        $profileIds = \Redis::SMEMBERS("followers:profile:".$loggedInProfileId);
        $ids = []; $ids2 = [];
        foreach ($chatProfileIds as $chatProfileId)
            $ids2[] = $chatProfileId;
        foreach ($profileIds as $profileId)
            $ids[] = (int)$profileId;

        $profileIds = array_diff($ids,$ids2);
        $count = count($profileIds);
        if($count > 0 && \Redis::sIsMember("followers:profile:".$loggedInProfileId,$loggedInProfileId)){
            $count = $count - 1;
        }
        $this->model['count'] = $count;
        $data = [];

        $page = $request->has('page') ? $request->input('page') : 1;
        $profileIds = array_slice($profileIds ,($page - 1)*20 ,20 );

        foreach ($profileIds as $key => $value)
        {
            if($loggedInProfileId == $value)
            {
                unset($profileIds[$key]);
                continue;
            }
            $profileIds[$key] = "profile:small:".$value ;
        }

        if(count($profileIds)> 0)
        {
            $data = \Redis::mget($profileIds);

        }
        foreach($data as &$profile){
            if(is_null($profile)){
                continue;
            }
            $profile = json_decode($profile);
            $profile->isFollowing = \Redis::sIsMember("followers:profile:".$profile->id,$loggedInProfileId) === 1;
            $profile->self = false;
        }
        $this->model['profile'] = $data;
        return $this->sendResponse();
    }

    public function getMembersToSearch(Request $request, $chatId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $chatProfileIds = \DB::table('chat_members')->where('chat_id',$chatId)->whereNull('deleted_at')->get()->pluck('profile_id');
        $this->model = [];
        $profileIds = \Redis::SMEMBERS("followers:profile:".$loggedInProfileId);
        $ids = []; $ids2 = [];
        foreach ($chatProfileIds as $chatProfileId)
            $ids2[] = $chatProfileId;
        foreach ($profileIds as $profileId)
            $ids[] = (int)$profileId;

        $profileIds = array_diff($ids,$ids2);
        $profileIds = array_diff($profileIds,$chatProfileIds);
        $query = $request->input('term');

        $this->model = \App\Recipe\Profile::select('profiles.*')->join('users','profiles.user_id','=','users.id')->where('users.name','like',"%$query%")
            ->whereIn('profiles.id',$profileIds)->take(15)->get();

        return $this->sendResponse();
    }

}
