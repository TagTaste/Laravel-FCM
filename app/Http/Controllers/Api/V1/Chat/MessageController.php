<?php

namespace App\Http\Controllers\Api\V1\Chat;

use App\Chat\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use App\Strategies\Paginator;
use App\Chat;
use Carbon\Carbon;

class MessageController extends Controller
{
    /**
     * Variable to model
     *
     * @var message
     */
    protected $model;
    protected $time;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(Message $model)
    {
        $this->model = $model;
        $this->time = \Carbon\Carbon::now()->toDateTimeString();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request, $chatId)
    {
        $loggedInProfileId = $request->user()->profile->id;

        if($this->isChatMember($loggedInProfileId, $chatId))
        {   
            $page = $request->input('page');
            list($skip,$take) = Paginator::paginate($page);
            $this->model = Message::join('message_recepients','chat_messages.id','=','message_recepients.message_id')
                ->where('chat_messages.chat_id',$chatId)->whereNull('message_recepients.deleted_on')
                ->where('message_recepients.recepient_id',$loggedInProfileId)->orderBy('message_recepients.sent_on','desc')->skip($skip)->take($take)->get();

            return $this->sendResponse();
        }
        else
        {
            return $this->sendError('This user is not a part of this chat');
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request, $chatId)
    {   
        $inputs = $request->all();
        $loggedInProfileId = $request->user()->profile->id;
        $checkExist = \App\Chat\Member::where('chat_id',$chatId)->where('profile_id',$loggedInProfileId)->exists();
        if($checkExist)
        {
            $parentMessageId = $request->input('parentMessageId')!=null ? $request->input('parentMessageId') : null;
            if(!isset($inputs['message']))
            {
                return $this->sendError("Message Field cannot be null");
            }
            $this->model = $this->model->create(['profile_id'=>$loggedInProfileId, 'chat_id'=>$chatId, 'message'=>$request->input('message'), 'parent_message_id'=>$parentMessageId]);
            $messageId = $this->model->id;

            if(isset($messageId))
            {
                $members = Chat\Member::where('chat_id',$chatId)->pluck('profile_id');
                foreach ($members as $profileId) {
                    \DB::table('message_recepients')->insert(['message_id'=>$messageId, 'recepient_id'=>$profileId, 'chat_id'=>$chatId, 'sent_on'=>$this->model["created_at"]]);
                }
            }
            return $this->sendResponse();
        }
        else
        {
            return $this->sendError("This user is not a part of this chat.");
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, $chatId, $id)
    {
        $loggedInProfileId = $request->user()->profile->id;
        if($this->isChatMember($loggedInProfileId, $chatId))
        {
            $this->model->findOrFail($id);
            return $this->sendResponse();
        }
        else
        {
            return $this->sendError("This user is not a part of this chat.");
        }
        
        
    }

    public function clearMessages(Request $request, $chatId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        if($this->isChatMember($loggedInProfileId, $chatId))
        {
            $this->model->ids = Message::where('chat_id',$chatId)->pluck('id');
            $this->model = \DB::table('message_recepients')->whereIn('message_id',$this->model->ids)->where('recepient_id',$loggedInProfileId)->update(['deleted_on'=>$this->time]);
            return $this->sendResponse();   
        }
        else
        {
            return $this->sendError('This user doesnt belong to this chat');
        }
    }

    public function destroy(Request $request, $id)
    {
        $loggedInProfileId = $request->user()->profile->id;
        \DB::table('message_recepients')->where('profile_id',$loggedInProfileId)->where('chat_message_id',$id)->update(['deleted_on'=>$this->time]);
    }


    protected function isChatMember($profileId, $chatId)
    {   
        return Chat::where('id',$chatId)->whereHas('members', function($query) use ($profileId){
            $query->withTrashed()->where('profile_id',$profileId);
        })->exists();
    }

    public function uploadFile(Request $request, $chatId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        if(!$this->isChatMember($loggedInProfileId, $chatId))
        {
            return $this->sendError("This user is not a part of this chat");
        }
        if($request->hasFile('file'))
        {
            $files = $request->file;
            foreach ($files as $file) 
            {   
                $ext = $file->getClientOriginalExtension();
                $fileName = str_random("32") . ".".$ext;
                $relativePath = "chat/$chatId/profile/$loggedInProfileId";
                $response = \Storage::url($file->storeAs($relativePath, $fileName,['visibility'=>'public']));
                if(!$response){
                    throw new \Exception("Could not save file " . $fileName . " at " . $relativePath);
                }
                    $parentMessageId = $request->input('parentMessageId')===null ? $request->input('parentMessageId') : null;
                    $preview = ["type"=>$ext, "link"=>$response];
                    $preview = json_encode($preview);
                        $this->model = $this->model->create(['chat_id'=>$chatId, 'profile_id'=>$loggedInProfileId,'parent_message_id'=>$parentMessageId, 'file'=>$response, 'preview'=>$preview]);//comment on git what to store in preview and i will do the needful. 
                        
            }
            return $this->sendResponse();       
        }

    }

    public function markAsRead(Request $request, $chatId)
    {   $loggedInProfileId = $request->user()->profile->id;
        $messageId = $request->input('messageId');
        if(!$this->isChatMember($loggedInProfileId, $chatId))
        {
            return $this->sendError("This user is not part of this chat");
        }
        $checkExist = Message::where('chat_id',$chatId)->where('id',$messageId)->exists();
        if(!$checkExist)
        {
            return $this->sendError('Invalid Message Id');
        }
        $messageIds = Message::where('chat_id',$chatId)->where('id','<=',$messageId)->pluck('id');
        $this->model = \DB::table('message_recepients')->where('recepient_id',$loggedInProfileId)->whereIn('message_id',$messageIds)->whereNull('read_on')->update(['read_on'=>$this->time]);
        return $this->sendResponse();
    }

    public function deleteChat(Request $request,$chatId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        if($this->isChatMember($loggedInProfileId, $chatId))
        {
            $this->model =  \DB::table('message_recepients')->where('recepient_id',$loggedInProfileId)->where('chat_id',$chatId)->update(['deleted'=>1,'deleted_on'=>$this->time]);
            return $this->sendResponse();
        }
        else{
            return $this->sendError("This user is not a part of this chat");
        }
    }

    public function deleteMessage(Request $request, $chatId)
    {   
        $loggedInProfileId = $request->user()->profile->id;
        $messageId = $request->input('messageId');
        if(!$this->isChatMember($loggedInProfileId, $chatId))
        {
            return $this->sendError("Invalid function on chat");
        }
        else
        {
            $this->model =  \DB::table('message_recepients')->where('recepient_id',$loggedInProfileId)->where('chat_id',$chatId)->where('message_id',$messageId)->update(['deleted_on'=>$this->time]);
            return $this->sendResponse();
        }
    }
}