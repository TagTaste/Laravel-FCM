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
        if(!$this->isMember($loggedInProfileId, $chatId))
        {
            return $this->sendError("This user doesnt belong to this chat");
        }
        $memberDeleted = Member::withTrashed()->where('profile_id',$loggedInProfileId)->where('chat_id',$chatId)->first()->deleted_at;
        if(is_null($memberDeleted))
        {
            $this->model = Member::where('chat_id',$chatId)->get();
        }
        else
        {
            $this->model = Member::where('chat_id',$chatId)->where('created_at','<=',$memberDeleted)->get();
        }
    	return $this->sendResponse();
    }

    public function store(Request $request,$chatId)
    {
    	$loggedInProfileId = $request->user()->profile->id;
    	if($this->getChatType($chatId) == 1)
    	{
    		return $this->sendError("This is one-to-one chat");
    	}
    	if(!$this->isAdmin($chatId,$loggedInProfileId))
    	{
    		return $this->sendError('Only group admins can perform this action');
    	}
    	$profileIds = $request->input('profileId');
    	if(!is_array($profileIds))
    		{
            	$profileIds = [$profileIds];
        	}
        	$members=Member::where('chat_id',$chatId)->pluck('profile_id'); 
        	$members = [$members];
    	$profileIds = array_diff($profileIds,$members);
        $input = [];
    	foreach ($profileIds as $profileId) {
            $deletedMember = Member::withTrashed()->where('chat_id',$chatId)->where('profile_id',$profileId)->first();
            if($deletedMember)
            {
                Member::withTrashed()->where('profile_id',$profileId)->update(['deleted_at'=>null, 'is_admin'=>0]);
            }
            else{
                $input[] = ['chat_id'=>$chatId,'profile_id'=>$profileId,'is_admin'=>0,'created_at'=>$this->time];
            }
    	}
    	$this->model = $this->model->insert($input);
    	return $this->sendResponse();
    }

    public function destroy(Request $request, $chatId, $profileId)
    {
    	if($this->getChatType($chatId) == 1)
    	{
    		return $this->sendError("invalid Function only valid on group chats");
    	}
    	$loggedInProfileId = $request->user()->profile->id;
    	if(!$this->isAdmin($chatId,$loggedInProfileId) && $loggedInProfileId!=$profileId)
    	{
    		return $this->sendError("This user cant perform this action");
    	}
    	if(Chat::where('id',$chatId)->pluck('profile_id')->first() == $profileId)
    	{
    		return $this->sendError('Super admin cannot be removed from the group');
    	}
    	$this->model = Member::where('chat_id',$chatId)->where('profile_id',$profileId)->delete();
    	return $this->sendResponse();

    }

    public function addAdmin(Request $request, $chatId)
    { 
    	$loggedInProfileId = $request->user()->profile->id;
        if($this->getChatType($chatId) === 1)
        {
            return $this->sendError("This operation is not possible in one-on-one chat");
        }
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
        return Chat::where('id',$chatId)->whereHas('members', function($query) use ($profileId){
            $query->withTrashed()->where('profile_id',$profileId);
        })->exists();
    }
}
