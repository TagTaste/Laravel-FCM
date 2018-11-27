<?php

namespace App\Http\Controllers\Api\Chat;

use App\Chat;
use App\Chat\Member;
use App\Http\Controllers\Api\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
	public function index(Request $request, $chatId)
	{
	    $profileId = $request->user()->profile->id;
	    
	    //check if profileId is member of given $chatId
		$memberOfChat = Member::withTrashed()->where('chat_id',$chatId)->where('profile_id',$profileId)->orderBy('created_at','desc')->first();

		if(!$memberOfChat){
		    return $this->sendError("Profile is not part of the chat.");
        }
        if(isset($memberOfChat->exited_on))
        {
            $this->model = Member::where('chat_id',$chatId)->where('profile_id','!=',$profileId)
                ->where('created_at','<=',$memberOfChat->exited_on)->whereNull('exited_on')->get();
        }
        else
        {
            $this->model = Member::where('chat_id',$chatId)->whereNull('exited_on')->get();
        }
		return $this->sendResponse();
	}
    
    /**
     * @param Request $request
     * @param $chatId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $chatId)
	{     \Log::info($request->all());
		$profileId = $request->user()->profile->id;
		
		//check ownership of chat.
        $chat =  Member::where('chat_id',$chatId)->where('is_admin',1)->where('profile_id',$profileId)->whereNull('deleted_at')->exists();
		if(!$chat){
		    return $this->sendError("Only chat admin can add members");
        }
        
        $profileIds = $request->input('profile_id');
		$data = [];
		$now = \Carbon\Carbon::now();
		foreach($profileIds as $currentProfileId){
		    $exists = Member::withTrashed()->where('chat_id',$chatId)->where('profile_id',$currentProfileId)->first();
		    if($exists)
            {
                $exists->update(['exited_on'=>null,'is_admin'=>0,'is_single'=>0]);
            }
            else
            {
                $data[] = ['chat_id'=>$chatId,'profile_id'=>$currentProfileId, 'created_at'=>$now->toDateTimeString(),'updated_at'=>$now->toDateTimeString(),'is_admin'=>0,'is_single'=>0];
            }
            $messageInfo = ['chat_id'=>$chatId,'profile_id'=>$profileId,'type'=>2, 'message'=>$profileId.'.'.\DB::table('chat_message_type')->where('id',2)->pluck('text')->first().'.'.$currentProfileId];
            event(new \App\Events\Chat\MessageTypeEvent($messageInfo));
        }
		$this->model = Member::insert($data);

		return $this->sendResponse();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id Profile Id
	 * @return Response
	 */
	public function destroy(Request $request, $chatId, $id)
	{
        $profileId = $request->user()->profile->id;
        
        //check ownership of chat.
        $chat =  Member::where('chat_id',$chatId)->where('is_admin',1)->where('profile_id',$profileId)->whereNull('deleted_at')->exists();
        if(!$chat && $id != $profileId){
            return $this->sendError("Only chat admin can remove members");
        }

        $this->model = Member::where('chat_id',$chatId)->where('profile_id',$id)->update(['exited_on'=>Carbon::now()->toDateTimeString(),'is_admin' => 0]);

        if($id == $profileId)
        {
            $messageInfo = ['chat_id'=>$chatId,'profile_id'=>$profileId,'type'=>4, 'message'=>$profileId.'.'.\DB::table('chat_message_type')->where('id',4)->pluck('text')->first().'.'.$id];
            event(new \App\Events\Chat\MessageTypeEvent($messageInfo));
            $adminExist = Member::where('chat_id',$chatId)->where('is_admin',1)->whereNull('exited_on')->exists();
            if(!$adminExist) {
                $member = Member::where('chat_id', $chatId)->whereNull('exited_on')->first();
                if($member){
                    $member->update(['is_admin' => 1]);
                }
            }
        }
        else
        {
            $messageInfo = ['chat_id'=>$chatId,'profile_id'=>$profileId,'type'=>3, 'message'=>$profileId.'.'.\DB::table('chat_message_type')->where('id',3)->pluck('text')->first().'.'.$id];
            event(new \App\Events\Chat\MessageTypeEvent($messageInfo));
        }
        return $this->sendResponse();
	}

    public function addAdmin(Request $request,$chatId)
    {
        $profileId = $request->user()->profile->id;

        //check ownership of chat.
        $chat =  Member::where('chat_id',$chatId)->where('is_admin',1)->where('profile_id',$profileId)->whereNull('deleted_at')->exists();
        if(!$chat){
            return $this->sendError("Only chat admin can remove members");
        }

        $profileIds = $request->input('profile_id');
        $this->model = $this->model->where('chat_id',$chatId)->whereIn('profile_id',$profileIds)->update(['is_admin'=>1]);
        foreach ($profileIds as $currentProfileId) {
            $messageInfo = ['chat_id'=>$chatId,'profile_id'=>$profileId,'type'=>7, 'message'=>$profileId.'.'.\DB::table('chat_message_type')->where('id',7)->pluck('text')->first().'.'.$currentProfileId];
            event(new \App\Events\Chat\MessageTypeEvent($messageInfo));
        }
        return $this->sendResponse();

    }

    public function removeAdmin(Request $request,$chatId)
    {
        $profileId = $request->user()->profile->id;

        //check ownership of chat.
        $chat =  Member::where('chat_id',$chatId)->where('is_admin',1)->where('profile_id',$profileId)->whereNull('deleted_at')->exists();
        if(!$chat){
            return $this->sendError("Only chat admin can remove members");
        }

        $profileIds = $request->input('profile_id');
        $this->model = $this->model->where('chat_id',$chatId)->whereIn('profile_id',$profileIds)->update(['is_admin'=>0]);
        foreach ($profileIds as $currentProfileId) {
            $messageInfo = ['chat_id'=>$chatId,'profile_id'=>$profileId,'type'=>8, 'message'=>$profileId.'.'.\DB::table('chat_message_type')->where('id',8)->pluck('text')->first().'.'.$currentProfileId];
            event(new \App\Events\Chat\MessageTypeEvent($messageInfo));
        }
        return $this->sendResponse();

    }
}
