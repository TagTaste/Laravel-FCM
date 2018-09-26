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
	    $profileId = $request->user()->profile->id;
        //check ownership
        
        $memberOfChat = Chat\Member::withTrashed()->where('chat_id',$chatId)->where('profile_id',$profileId)->orderBy('created_at','desc')->first();
        
        if(!$memberOfChat) {
            return $this->sendError("You are not part of this chat.");
        }
        
        $page = $request->input('page');
        list($skip,$take) = Paginator::paginate($page);
        $isEnabled = true;
        if(isset($memberOfChat->exited_on))
        {
            $data = $this->model->where('chat_id',$chatId)->whereBetween('created_at',[$memberOfChat->updated_at,$memberOfChat->exited_on])
                ->orderBy('created_at','desc')->skip($skip)->take($take)->get();
            $isEnabled = false;
        }
        else
        {
            $data = $this->model->where('chat_id',$chatId)->where('created_at','>=',$memberOfChat->created_at)
                ->orderBy('created_at','desc')->skip($skip)->take($take)->get();
        }
        $this->model = [];
        $this->model['data'] = $data;
        $this->model['is_enabled'] = $isEnabled;
		return $this->sendResponse();
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

        $chat = Chat\Member::where('chat_id',$chatId)->where('profile_id','!=',$profileId)->update(['last_seen'=>null]);

        if($memberOfChat->is_single){
            //undelete other members
            
            $otherMemberOfChat = Chat\Member::withTrashed()->where('chat_id',$chatId)->where("profile_id",'!=',$profileId)
                ->whereNotNull('deleted_at')->first();
            
            if($otherMemberOfChat){
                //restore if deleted
                $data = [];
                if($otherMemberOfChat->trashed()){
                    $data['deleted_at'] = null;
                }
                
                $data['exited_at'] = null;
                //set exited to null, if exited;
                $otherMemberOfChat->update($data);
            }
        }
        
        if($request->hasFile("file"))
        {
            $path = "profile/$profileId/chat/$chatId/file";
            $filename = $request->file('file')->getClientOriginalName();
    
            $inputs['file'] = $request->file("file")->storeAs($path, $filename,['visibility'=>'public']);
        }

        if(isset($inputs['preview']['image']) && !empty($inputs['preview']['image'])){
            $image = $this->getExternalImage($inputs['preview']['image'],$profileId);
            $s3 = \Storage::disk('s3');
            $filePath = 'p/' . $profileId . "/ci";
            $resp = $s3->putFile($filePath, new File(storage_path($image)), 'public');
            $inputs['preview']['image'] = $resp;
        }
        \Log::info($inputs['preview']);
        if(isset($inputs['preview']))
        {
            $inputs['preview'] = json_encode($inputs['preview']);
        }
        else
        {
            $inputs['preview'] = null;
        }

        $inputs['chat_id'] = $chatId;
        $inputs['profile_id'] = $profileId;
		$this->model = $this->model->create($inputs);
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
        $this->model = $this->model->where("chat_id",$chatId)
            ->where('id',$id)->where(function($query) use ($profileId,$loggedInProfileId){
                $query->where('profile_id','=',$profileId)->orWhere('profile_id','=',$loggedInProfileId);
            })->destroy();

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
        
        $this->model = $this->model->where('chat_id',$chatId)->update(['read_on'=>$now]);
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