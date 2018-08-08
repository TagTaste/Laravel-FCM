<?php

namespace App\Http\Controllers\Api;

use App\Chat;
use App\CompanyUser;
use App\Strategies\Paginator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use App\Mail\JobResponse;

class ChatController extends Controller
{
    /**
     * Variable to model
     *
     * @var chat
     */
    protected $model;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(Chat $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $profileId = $request->user()->profile->id;

        $page = $request->input('page');
        list($skip,$take) = Paginator::paginate($page);
        $this->model = Chat::whereHas('members',function($query) use ($profileId) {
            $query->where('profile_id',$profileId)->whereNull('deleted_at');
        })->skip($skip)->take($take)->orderByRaw('updated_at desc, created_at desc')->get();

        return $this->sendResponse();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $inputs = $request->except(['_method','_token','isSingle']);

        //set profile_id to logged in user automatically.
        //all profileIds passed in request would be added to Chat\Member;
        $profileIds = $inputs['profile_id'];
        if(!is_array($profileIds)){
            $profileIds = [$profileIds];
        }
        $user = $request->user();
        $loggedInProfileId = $user->profile->id;
        $inputs['profile_id'] = $loggedInProfileId;
        //check for existing chats only for single profileId.
        if(is_array($profileIds) && count($profileIds) === 1 && $request->input('isSingle') == 1){
            $existingChats = Chat::open($profileIds[0],$loggedInProfileId);
            \Log::info($existingChats);
            if(!is_null($existingChats) && $existingChats->count() > 0){
                $this->messages[] = "chat_open";
                $this->model = $existingChats;
                return $this->sendResponse();
            }

            if(!\Redis::sIsMember("followers:profile:".$loggedInProfileId,$profileIds[0]))
            {
                if(!\App\ChatLimit::checkLimit($loggedInProfileId,$profileIds[0])){
                    return $this->sendError("max_limit_reached");
                }
            }
        }

        $this->model = \App\Chat::create($inputs);
        if($request->hasFile("image")){
            $imageName = str_random("32") . ".jpg";
            $path = Chat::getImagePath($this->model->id);
            $response = $request->file('image')->storeAs($path,$imageName,['visibility'=>'public']);
            if(!$response){
                throw new \Exception("Could not save image " . $imageName . " at " . $path);
            }
            $this->model->update(['image'=>$response]);
        }

        //add members to the chat
        $now = \Carbon\Carbon::now()->toDateTimeString();
        $data = [];
        $chatId = $this->model->id;
        //for add login profile id in member model
        $data[] = ['chat_id'=>$chatId,'profile_id'=>$loggedInProfileId, 'created_at'=>$now,'updated_at'=>$now,'is_admin'=>1,'is_single'=>$request->input('isSingle')];
        foreach($profileIds as $profileId){
            $data[] = ['chat_id'=>$chatId,'profile_id'=>$profileId, 'created_at'=>$now,'updated_at'=>$now,'is_admin'=>0,'is_single'=>$request->input('isSingle')];
        }
        $this->model->members()->insert($data);

        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request ,$id)
    {
        $profileId = $request->user()->profile->id;

        //current user should be part of the chat, is a sufficient condition.
        $this->model = Chat::where('id',$id)->whereHas('members',function($query) use ($profileId) {
            $query->where('profile_id',$profileId)->whereNull('deleted_at');
        })->get();
        return $this->sendResponse();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->all();

        $chat = $this->model->findOrFail($id);

        if($request->hasFile("image")){
            $imageName = str_random("32") . ".jpg";
            $path = Chat::getImagePath($chat->id);
            $response = $request->file('image')->storeAs($path,$imageName,['visibility'=>'public']);
            if(!$response){
                throw new \Exception("Could not save image " . $imageName . " at " . $path);
            }
            $inputs['image'] = $response;
        }
        unset($inputs['profile_id']);
        $this->model = $chat->update($inputs);

        //add members to the chat
        $profileIds = $request->input('profile_id');
        $now = \Carbon\Carbon::now()->toDateTimeString();
        $data = [];
        if(count($profileIds)) {
            foreach ($profileIds as $profileId) {
                \Log::info($profileId);
                $data[] = ['chat_id' => $id, 'profile_id' => $profileId, 'created_at' => $now,'updated_at'=>$now,'is_admin'=>0];
            }
            $chat->members()->insert($data);
        }
        return $this->sendResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request,$chadId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $this->model = Chat\Member::where('chat_id',$chadId)->where('profile_id',$loggedInProfileId)
            ->update(['deleted_at'=>Carbon::now()->toDateTimeString()]);
        \App\ChatLimit::increaseLimit($loggedInProfileId);
        return $this->sendResponse();
    }

    public function rooms(Request $request)
    {
        $profileId = $request->user()->profile->id;
        $this->model = \DB::table('chats')->select('chats.id')
            ->join('chat_members','chat_members.chat_id','=','chats.id')
            ->where('chat_members.profile_id','=',$profileId)->whereNull('chat_members.deleted_at')->get();
        return $this->sendResponse();
    }

    public function chatGroup (Request $request)
    {
        $profileId = $request->user()->profile->id;

        $this->model = Chat::select('chats.*')->join('chat_members','chat_members.chat_id','=','chats.id')
            ->where('chat_members.is_single',0)->where('chat_members.profile_id','=',$profileId)->whereNotNull('chats.name')
            ->whereNull('chat_members.deleted_at')->whereNull('chat_members.exited_on')->groupBy('chats.id')->get();

        return $this->sendResponse();

    }

    public function chatShareMessage(Request $request)
    {
        $profileIds = $request->input('profile_id');
        $chatIds = $request->input('chat_id');
        $inputs = $request->all();

        event(new \App\Events\Chat\ShareMessage($chatIds,$profileIds,$inputs,$request->user()));

        $this->model = true;

        return $this->sendResponse();

    }

    public function chatMessage(Request $request)
    {
        $inputs = $request->except(['_method','_token','isSingle']);

        //set profile_id to logged in user automatically.
        //all profileIds passed in request would be added to Chat\Member;
        $profileIds = $inputs['profile_id'];
        if(!is_array($profileIds)){
            $profileIds = [$profileIds];
        }
        $user = $request->user();
        $loggedInProfileId = $user->profile->id;
        $inputs['profile_id'] = $loggedInProfileId;
        //check for existing chats only for single profileId.
        if(is_array($profileIds) && count($profileIds) === 1 && $request->input('isSingle') == 1){
            $existingChats = Chat::open($profileIds[0],$loggedInProfileId);
            if(!is_null($existingChats) && $existingChats->count() > 0){
                $this->messages[] = "chat_open";
                $this->model = $existingChats;
                return $this->sendmessage($request,$inputs);
            }

            if(!\Redis::sIsMember("followers:profile:".$loggedInProfileId,$profileIds[0]))
            {
                if(!\App\ChatLimit::checkLimit($loggedInProfileId,$profileIds[0])){
                    return $this->sendError("max_limit_reached");
                }
            }

            $this->model = \App\Chat::create($inputs);
            if($request->hasFile("image")){
                $imageName = str_random("32") . ".jpg";
                $path = Chat::getImagePath($this->model->id);
                $response = $request->file('image')->storeAs($path,$imageName,['visibility'=>'public']);
                if(!$response){
                    throw new \Exception("Could not save image " . $imageName . " at " . $path);
                }
                $this->model->update(['image'=>$response]);
            }

            //add members to the chat
            $now = \Carbon\Carbon::now()->toDateTimeString();
            $data = [];
            $chatId = $this->model->id;
            //for add login profile id in member model
            $data[] = ['chat_id'=>$chatId,'profile_id'=>$loggedInProfileId, 'created_at'=>$now,'updated_at'=>$now,'is_admin'=>1,'is_single'=>$request->input('isSingle')];
            foreach($profileIds as $profileId){
                $data[] = ['chat_id'=>$chatId,'profile_id'=>$profileId, 'created_at'=>$now,'updated_at'=>$now,'is_admin'=>0,'is_single'=>$request->input('isSingle')];
            }
            $this->model->members()->insert($data);
            return $this->sendmessage($request,$inputs);

        }
        else
        {
            $this->model = \App\Chat::create($inputs);
            if($request->hasFile("image")){
                $imageName = str_random("32") . ".jpg";
                $path = Chat::getImagePath($this->model->id);
                $response = $request->file('image')->storeAs($path,$imageName,['visibility'=>'public']);
                if(!$response){
                    throw new \Exception("Could not save image " . $imageName . " at " . $path);
                }
                $this->model->update(['image'=>$response]);
            }

            //add members to the chat
            $now = \Carbon\Carbon::now()->toDateTimeString();
            $data = [];
            $chatId = $this->model->id;
            //for add login profile id in member model
            $data[] = ['chat_id'=>$chatId,'profile_id'=>$loggedInProfileId, 'created_at'=>$now,'updated_at'=>$now,'is_admin'=>1,'is_single'=>$request->input('isSingle')];
            foreach($profileIds as $profileId){
                $data[] = ['chat_id'=>$chatId,'profile_id'=>$profileId, 'created_at'=>$now,'updated_at'=>$now,'is_admin'=>0,'is_single'=>$request->input('isSingle')];
            }
            $this->model->members()->insert($data);

            return $this->sendResponse();
        }

    }

    private function sendmessage($request,$inputs)
    {
        $loggedInProfileId = $request->user()->profile->id;
        if(($request->has('message') && !empty($request->input('message'))) || $request->hasFile("file"))
        {
            $chatId = $this->model->id;
            if($request->hasFile("file"))
            {
                $path = "profile/$loggedInProfileId/chat/$chatId/file";
                $filename = $request->file('file')->getClientOriginalName();

                $inputs['file'] = $request->file("file")->storeAs($path, $filename,['visibility'=>'public']);
            }

            if(isset($inputs['preview']['image']) && !empty($inputs['preview']['image'])){
                $image = $this->getExternalImage($inputs['preview']['image'],$loggedInProfileId);
                $s3 = \Storage::disk('s3');
                $filePath = 'p/' . $loggedInProfileId . "/ci";
                $resp = $s3->putFile($filePath, new File(storage_path($image)), 'public');
                $inputs['preview']['image'] = $resp;
            }
            if(isset($inputs['preview']))
            {
                $inputs['preview'] = json_encode($inputs['preview']);
            }

            $inputs['chat_id'] = $chatId;
            $inputs['profile_id'] = $loggedInProfileId;
            $this->model = [];
            $this->model['data'] = Chat\Message::create($inputs);
            $remaining = \DB::table('chat_limits')->select('remaining')->where('profile_id',$loggedInProfileId)->first();
            $this->model['remaining_messages'] = isset($remaining->remaining) ? $remaining->remaining : null;
//        $this->model = Chat\Message::where
            event(new \App\Events\Chat\Message($this->model['data'],$request->user()->profile));

            return $this->sendResponse();
        }

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
        $profileIds = isset($inputs['profile_id']) ? $inputs['profile_id'] : [];
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
        $class = "\\App\\" . ucwords($feature);
        return $class::find($featureId);
    }
}