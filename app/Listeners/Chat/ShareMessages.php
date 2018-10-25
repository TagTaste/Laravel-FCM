<?php

namespace App\Listeners\Chat;

use App\Chat\Message;
use App\Events\Chat\ShareMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\File;

class ShareMessages implements ShouldQueue
{
    use Queueable;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Message  $event
     * @return void
     */
    public function handle(ShareMessage $event)
    {
        $chatIds = $event->chatIds;
        $loggedInProfileId = $event->user->profile->id;

        $inputs = $event->inputs;
        if(is_string($inputs['preview']))
        {
            $inputs['preview'] = isset($inputs['preview']) ? json_decode($inputs['preview'],true) : null;
        }
        else
        {
            $inputs['preview'] = isset($inputs['preview']) ? $inputs['preview'] : null;
        }
        $profileIds = $event->profileIds;
        if(count($chatIds))
        {
            foreach ($chatIds as $chatId)
            {
                $info = [];
                if(isset($inputs['preview']['image']) && !empty($inputs['preview']['image'])){
                    $image = $this->getExternalImage($inputs['preview']['image'],$loggedInProfileId);
                    $s3 = \Storage::disk('s3');
                    $filePath = 'p/' . $loggedInProfileId . "/ci";
                    $resp = $s3->putFile($filePath, new File(storage_path($image)), 'public');
                    $ext= pathinfo($resp);
                    $ext = isset($ext['extension']) ? $ext['extension'] : null;
                    if($resp && ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png')){
                        $inputs['preview']['image'] = $resp;
                    }
                    else
                    {
                        $inputs['preview']['image'] = null;
                    }
                    if($resp)
                    {
                        \File::delete(storage_path($image));
                    }
                }
                if(isset($inputs['preview']))
                {
                    $info['preview'] = json_encode($inputs['preview']);
                }
                else
                {
                    $inputs['preview'] = null;
                }

                $info['chat_id'] = $chatId;
                $info['profile_id'] = $loggedInProfileId;
                $info['message'] = $inputs['message'];
                $chat = Message::create($info);

                event(new \App\Events\Chat\Message($chat,$event->user->profile));

            }

        }
        if(count($profileIds))
        {
            $chatIds = \DB::table("chat_members as c1")->selectRaw(\DB::raw("c1.chat_id as chat_id , c2.profile_id as profile_id"))
                ->join('chat_members as c2','c2.chat_id','=','c1.chat_id')
                ->join("chats",'chats.id','=','c1.chat_id')
                ->where(function($query) use ($loggedInProfileId){
                    $query->where('c1.profile_id','=',$loggedInProfileId)->where('c1.is_single','=',1);
                })->where(function($query) use ($profileIds) {
                    $query->whereIn('c2.profile_id',$profileIds)->where('c2.is_single','=',1)
                    ;
                })->whereNull('chats.deleted_at')
                ->orderBy('c1.chat_id')
                ->get();
            $chatProfileIds = $chatIds->pluck('profile_id');
            $chatIds = $chatIds->pluck('chat_id');
            foreach ($chatIds as $chatId)
            {
                $info = [];
                if(isset($inputs['preview']['image']) && !empty($inputs['preview']['image'])){
                    $image = $this->getExternalImage($inputs['preview']['image'],$loggedInProfileId);
                    $s3 = \Storage::disk('s3');
                    $filePath = 'p/' . $loggedInProfileId . "/ci";
                    $resp = $s3->putFile($filePath, new File(storage_path($image)), 'public');
                    $ext= pathinfo($resp);
                    $ext = isset($ext['extension']) ? $ext['extension'] : null;
                    if($resp && ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png')){
                        $inputs['preview']['image'] = $resp;
                    }
                    else
                    {
                        $inputs['preview']['image'] = null;
                    }
                    if($resp)
                    {
                        \File::delete(storage_path($image));
                    }
                }
                if(isset($inputs['preview']))
                {
                    $info['preview'] = json_encode($inputs['preview']);
                }
                else
                {
                    $inputs['preview'] = null;
                }

                $info['chat_id'] = $chatId;
                $info['profile_id'] = $loggedInProfileId;
                $info['message'] = $inputs['message'];
                $chat = Message::create($info);

                event(new \App\Events\Chat\Message($chat,$event->user->profile));
            }
            $profileIds = array_diff($profileIds,$chatProfileIds->toArray());
            $now = \Carbon\Carbon::now()->toDateTimeString();
            foreach ($profileIds as $profileId)
            {
                $info = [];
                $info['profile_id'] = $loggedInProfileId;
                $this->model = \App\Chat::create($info);
                $data = [];
                $chatId = $this->model->id;
                $data[] = ['chat_id'=>$chatId,'profile_id'=>$profileId, 'created_at'=>$now,'updated_at'=>$now,'is_admin'=>0,'is_single'=>1];
                $data[] = ['chat_id'=>$chatId,'profile_id'=>$loggedInProfileId, 'created_at'=>$now,'updated_at'=>$now,'is_admin'=>0,'is_single'=>1];
                $this->model->members()->insert($data);
                $info = [];
                if(isset($inputs['preview']['image']) && !empty($inputs['preview']['image'])){
                    $image = $this->getExternalImage($inputs['preview']['image'],$profileId);
                    $s3 = \Storage::disk('s3');
                    $filePath = 'p/' . $profileId . "/ci";
                    $resp = $s3->putFile($filePath, new File(storage_path($image)), 'public');
                    $inputs['preview']['image'] = $resp;
                }
                if(isset($inputs['preview']))
                {
                    $info['preview'] = json_encode($inputs['preview']);
                }
                else
                {
                    $inputs['preview'] = null;
                }

                $info['chat_id'] = $chatId;
                $info['profile_id'] = $loggedInProfileId;
                $info['message'] = $inputs['message'];
                $chat = Message::create($info);

                event(new \App\Events\Chat\Message($chat,$event->user->profile));
            }
        }

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
