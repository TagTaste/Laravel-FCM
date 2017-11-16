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

        if(isset($memberOfChat->deleted_at))
        {
            $this->model = Member::where('chat_id',$chatId)->where('created_at','<=',$memberOfChat->deleted_at)->get();
        }
        else
        {
            $this->model = Member::where('chat_id',$chatId)->get();
        }
		return $this->sendResponse();
	}
    
    /**
     * @param Request $request
     * @param $chatId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $chatId)
	{
		$profileId = $request->user()->profile->id;
		
		//check ownership of chat.
        $chat =  Member::where('chat_id',$chatId)->where('is_admin',1)->where('is_single',0)->where('profile_id',$profileId)->whereNull('deleted_at')->exists();
		if(!$chat){
		    return $this->sendError("Only chat admin can add members");
        }
        
        $profileIds = $request->input('profile_id');
		$data = [];
		$chatProfileIds = [];
		$now = \Carbon\Carbon::now();
		foreach($profileIds as $profileId){
		    $data[] = ['chat_id'=>$chatId,'profile_id'=>$profileId, 'created_at'=>$now->toDateTimeString(),'is_admin'=>0,'is_single'=>0];
            $chatProfileIds[] = ['chat_id'=>$chatId,'profile_id'=>$profileId, 'created_at'=>$now->toDateTimeString()];
        }
        \DB::table('chat_profiles')->insert($chatProfileIds);
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

        $this->model = Member::where('chat_id',$chatId)->where('profile_id',$id)->delete();
        if($id==$profileId)
        {
            $adminExist = Member::where('chat_id',$chatId)->where('is_admin',1)->whereNull('deleted_at')->exists();
            if(!$adminExist) {
                $member = Member::where('chat_id', $chatId)->whereNull('deleted_at')->first();
                $member->update(['is_admin' => 1]);
            }
        }
        return $this->sendResponse();
	}

    public function addAdmin(Request $request,$chatId)
    {
        $profileIds = $request->input('profile_id');
        $this->model = $this->model->where('chat_id',$chatId)->whereIn('profile_id',$profileIds)->update(['is_admin'=>1]);
        return $this->sendResponse();

    }
}
