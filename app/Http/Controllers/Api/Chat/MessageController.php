<?php

namespace App\Http\Controllers\Api\Chat;

use App\Chat;
use App\Chat\Message;
use App\Http\Controllers\Api\Controller;
use App\Strategies\Paginator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\File;

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
	public function index(Request $request,$chatId)
	{
        $loggedInProfileId = $request->user()->profile->id;

        if($this->isChatMember($loggedInProfileId, $chatId))
        {   
            $page = $request->input('page');
            list($skip,$take) = Paginator::paginate($page);
            $data = Message::join('message_recepients','chat_messages.id','=','message_recepients.message_id')
                ->where('chat_messages.chat_id',$chatId)->whereNull('message_recepients.deleted_on')
            ->where('message_recepients.recepient_id',$loggedInProfileId)->orderBy('message_recepients.sent_on','desc')->where('type',0)->skip($skip)->take($take)->get();
            $this->model = [];
            $this->model['data'] = $data;
            $this->model['is_enabled'] = null;
            return $this->sendResponse();
        }
        else
        {
            return $this->sendError('This user is not a part of this chat');
        }
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request, $chatId)
	{
		$inputs = $request->except(['file']);
        $profileId = $request->user()->profile->id;
        //check ownership
        
        $memberOfChat = Chat\Member::where('chat_id',$chatId)->where('profile_id',$profileId)->whereNull('exited_on')->first();
        
        if(!$memberOfChat) {
            return $this->sendError("You are not part of this chat.");
        }

        //$chat = Chat\Member::where('chat_id',$chatId)->where('profile_id','!=',$profileId)->update(['last_seen'=>null]);

        
        if($request->hasFile("file"))
        {
            $path = "profile/$profileId/chat/$chatId/file";
            $filename = $request->file('file')->getClientOriginalName();
    
            $inputs['file'] = $request->file("file")->storeAs($path, $filename,['visibility'=>'public']);
        }
        $inputs['preview'] = isset($inputs['preview']) ? json_decode($inputs['preview'],true) : null;
        if(isset($inputs['preview']['image']) && !empty($inputs['preview']['image'])){
            $image = $this->getExternalImage($inputs['preview']['image'],$profileId);
            $s3 = \Storage::disk('s3');
            $filePath = 'p/' . $profileId . "/ci";
            $resp = $s3->putFile($filePath, new File(storage_path($image)), 'public');
            $inputs['preview']['image'] = $resp;
        }
        if(isset($inputs['preview']))
        {
            $inputs['preview'] = json_encode($inputs['preview']);
        }
        $inputs['chat_id'] = $chatId;
        $inputs['profile_id'] = $profileId;
		$this->model = $this->model->create($inputs);
        $messageId = $this->model->id;
        if($memberOfChat->is_single){
            //undelete other members
            
            $otherMemberOfChat = Chat\Member::withTrashed()->where('chat_id',$chatId);
            if($otherMemberOfChat->exists()){
                //restore if deleted
                $data = [];
                if($otherMemberOfChat->onlyTrashed()->exists() ){
                    $data['deleted_at'] = null;
                }
                
                //$data['exited_at'] = null;
                //set exited to null, if exited;
                $otherMemberOfChat->update($data);
            }
        }
        foreach (Chat\Member::where('chat_id',$chatId)->pluck('profile_id') as $currentProfileId) {
                    if($currentProfileId == $profileId)
                    {
                        \DB::table('message_recepients')->insert(['message_id'=>$messageId, 'recepient_id'=>$currentProfileId, 'chat_id'=>$chatId, 'sent_on'=>$this->model["created_at"], 'read_on' => $this->model["created_at"]]);
                    }
                    else
                    {
                            \DB::table('message_recepients')->insert(['message_id'=>$messageId, 'recepient_id'=>$currentProfileId, 'chat_id'=>$chatId, 'sent_on'=>$this->model["created_at"]]);
                    }
                }
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
        
        $memberOfChat = Chat\Member::where('chat_id',$chatId)->where('profile_id',$loggedInProfileId)->whereNull('exited_on')->exists();
        
        if(!$memberOfChat) {
            return $this->sendError("You are not part of this chat.");
        }
        
        $profileId = $request->input('profile_id');
        $this->model = \DB::table('message_recepients')->where('chat_id',$chatId)->where('message_id',$id)->where('recepient_id',$loggedInProfileId)->update(['deleted_on'=>Carbon::now()->toDateTimeString()]);

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
        
        $this->model = \DB::table('message_recepients')->where('chat_id',$chatId)->where('message_id','<=',$id)->where('recepient_id',$loggedInProfileId)->update(['read_on'=>Carbon::now()->toDateTimeString()]);
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

    protected function isChatMember($profileId, $chatId)
    {   
        return Chat::where('id',$chatId)->whereHas('members', function($query) use ($profileId){
            $query->where('profile_id',$profileId);
        })->exists();
    }
}