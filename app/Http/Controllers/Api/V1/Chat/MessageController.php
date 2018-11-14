<?php

namespace App\Http\Controllers\Api\V1\Chat;

use App\Chat\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use App\Strategies\Paginator;
use App\Chat;
use Carbon\Carbon;
use Illuminate\Http\Testing\MimeType;

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
            if(isset($inputs['preview']) && !empty($inputs['preview']))
            {
                if(isset($inputs['preview']['image']) && !empty($inputs['preview']['image'])){
                $image = $this->getExternalImage($inputs['preview']['image'],$loggedInProfileId);
                $s3 = \Storage::disk('s3');
                $filePath = 'p/' . $loggedInProfileId . "/ci";
                $resp = $s3->putFile($filePath, new File(storage_path($image)), 'public');
                $inputs['preview']['image'] = $resp;
                }
                $preview = $inputs['preview'];
            }
            else
            {
                $preview = null;
            }
            $this->model = Message::create(['profile_id'=>$loggedInProfileId, 'chat_id'=>$chatId, 'message'=>$request->input('message'), 'parent_message_id'=>$parentMessageId, 'preview'=> $preview, 'signature'=>$request->input('signature')]);
            $messageId = $this->model->id;

            if(isset($messageId))
            {
                $members = Chat\Member::where('chat_id',$chatId)->pluck('profile_id');
                foreach ($members as $profileId) {
                    if($profileId == $loggedInProfileId)
                    {
                        \DB::table('message_recepients')->insert(['message_id'=>$messageId, 'recepient_id'=>$profileId, 'chat_id'=>$chatId, 'sent_on'=>$this->model["created_at"], 'read_on' => $this->model["created_at"]]);
                    }
                    else
                    {
                            \DB::table('message_recepients')->insert(['message_id'=>$messageId, 'recepient_id'=>$profileId, 'chat_id'=>$chatId, 'sent_on'=>$this->model["created_at"]]);
                    }
                }
                return $this->sendResponse();
            }
        
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
            $caption = $request->caption;
            $files = $request->file;
            $storeFile = [];
            $imageFormat = ['JPG','PNG','JPEG','GIF'];
            foreach ($files as $key => $file) 
            {   
                $ext = $file->getClientOriginalExtension();
                $originalName = $file->getClientOriginalName();
                $fileName = str_random("32") . ".".$ext;
                $relativePath = "/chat/$chatId/profile/$loggedInProfileId";
                $response['original_photo'] = \Storage::url($file->storeAs($relativePath, $fileName,['visibility'=>'public']));
                if(!$response){
                    throw new \Exception("Could not save file " . $fileName . " at " . $relativePath);
                }
                if(in_array($ext, $imageFormat))
                {
                    $tinyImagePath = "chat/$chatId/profile/$loggedInProfileId/tiny/".str_random("20").".".$ext;
                $thumbnail = \Image::make($file)->resize(50, null,function ($constraint) {
                $constraint->aspectRatio();
                })->blur(1)->stream('jpg',70);
                \Storage::disk('s3')->put($tinyImagePath, (string) $thumbnail,['visibility'=>'public']);
                $response['tiny_photo'] = \Storage::url($tinyImagePath);
                $response['meta'] = getimagesize($file);
                array_push($response['meta'], $response['tiny_photo']);
                }
                else
                {   
                    if(strpos($file->getClientMimeType(), 'video') !== false)
                    {
                        $mediaJson = $this->videoTranscodingNew($response['original_photo']);
                        $response['meta'] = json_decode($mediaJson);
                        $mime = ["mime"=>$file->getClientMimeType()];
                        $response['meta'] = (array) $response['meta'];
                        array_push($response['meta'], $mime);
                    }
                    else
                        $response['meta'] = ["mime"=>$file->getClientMimeType(),"size"=>$file->getClientSize()/(1024*1024)];
                }
                    $thisCaption = isset($caption[$key]) ? $caption[$key] : null;
                    if($key == 0)
                    {
                        $parentMessageId = $request->input('parentMessageId')!=null ? $request->input('parentMessageId') : null;
                    }
                    else
                    {
                        $parentMessageId = null;
                    }
                     $file_meta = ["original_name"=>$originalName, "original_link"=>$response['original_photo'], "meta"=>$response['meta']];
                    $file_meta = json_encode($file_meta);
                 $this->model = $this->model->create(['chat_id'=>$chatId, 'profile_id'=>$loggedInProfileId,'parent_message_id'=>$parentMessageId, 'file'=>$response['original_photo'], 'file_meta'=>$file_meta, 'message'=>$thisCaption]);//comment on git what to store in preview and i will do the needful. 
                 $messageId = $this->model->id;
                 $storeFile[] = $this->model;
                 $members = Chat\Member::where('chat_id',$chatId)->pluck('profile_id');
                foreach ($members as $profileId) {
                    if($profileId == $loggedInProfileId)
                    {
                        \DB::table('message_recepients')->insert(['message_id'=>$messageId, 'recepient_id'=>$profileId, 'chat_id'=>$chatId, 'sent_on'=>$this->model["created_at"], 'read_on' => $this->model["created_at"]]);
                    }
                    else
                    {
                            \DB::table('message_recepients')->insert(['message_id'=>$messageId, 'recepient_id'=>$profileId, 'chat_id'=>$chatId, 'sent_on'=>$this->model["created_at"]]);
                    }
                }
                 
                        
            }
            $this->model = $storeFile;
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
        if(!is_array($messageId)){
            $messageId = [$messageId];
        }
        if(!$this->isChatMember($loggedInProfileId, $chatId))
        {
            return $this->sendError("Invalid function on chat");
        }
        else
        {
            $this->model =  \DB::table('message_recepients')->where('recepient_id',$loggedInProfileId)->where('chat_id',$chatId)->whereIn('message_id',$messageId)->update(['deleted_on'=>$this->time]);
            return $this->sendResponse();
        }
    }

    private function videoTranscodingNew($url)
    {

        $profileId = request()->user()->profile->id;
        $curl = curl_init();
        $data = [
            'profile_id' => $profileId,
            'file_path' => $url
        ];
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('TRANSCODING_APIGATEWAY_URL'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));
        $response = curl_exec($curl);
        $response = json_decode($response);
        $body = $response->body;
        return json_encode($body,true);
    }
}