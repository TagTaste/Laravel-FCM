<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use App\V1\Chat\Message;
use App\V1\Chat\Member;
use App\V1\Chat;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

class SendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    
    protected $profileIds;
    protected $chatIds;
    protected $loggedInProfileId;
    protected $message;
    protected $preview;

    public function __construct($profileIds, $chatIds, $loggedInProfileId, $message, $preview)
    {
        $this->profileIds = $profileIds;
        $this->chatIds = $chatIds;
        $this->loggedInProfileId = $loggedInProfileId;
        $this->message = $message;
        $this->preview = $preview;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(isset($this->profileIds) && count($this->profileIds))
        {
            foreach ($this->profileIds as $profileId) {
                Log::info("Creating chat between {$this->loggedInProfileId} and {$profileId}");
                $chat = Chat::open($this->loggedInProfileId, $profileId);
                if (!$chat) {
                    $chat = Chat::create(['profile_id' => $this->loggedInProfileId, 'chat_type' => 1]);
                    $input = [
                        ['chat_id' => $chat->id, 'profile_id' => $this->loggedInProfileId, 'is_admin' => 1],
                        ['chat_id' => $chat->id, 'profile_id' => $profileId, 'is_admin' => 0]
                    ];
                    Member::insert($input);
                }
                Log::info("New chat created with ID: {$chat->id}");

                Log::info("Sending message: {$this->message} to chat ID: {$chat->id}");
                $this->addMessageAndRecepients($this->message, $this->loggedInProfileId, $this->preview, $chat->id);
                Log::info("Message sent successfully.");
            }
        }
        if(isset($this->chatIds) && count($this->chatIds))
        {
            foreach ($this->chatIds as $chatId){
                $isMember = Member::withTrashed()->where('chat_id',$chatId)->where('profile_id',$this->loggedInProfileId)->whereNull('exited_on')->exists();
                if($isMember)
                {
                    Log::info("Sending message: {$this->message} to chat ID: {$chatId}");
                    $this->addMessageAndRecepients($this->message, $this->loggedInProfileId, $this->preview, $chatId);
                    Log::info("Message sent successfully.");
                }
            }
        }
    }

    public function addMessageAndRecepients($messageToSend, $loggedInProfileId, $previewData, $chat_id){
        $latestMessageId = \DB::table('chat_messages')->insertGetId(['message'=>$messageToSend, 'profile_id'=>$loggedInProfileId, 'preview'=>$previewData, 'chat_id'=>$chat_id, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()]);
        $message = \DB::table('chat_messages')->find($latestMessageId);
        $members = Member::withTrashed()->where('chat_id',$message->chat_id)->whereNull('exited_on')->pluck('profile_id');
        Member::where('chat_id',$message->chat_id)->onlyTrashed()->update(['deleted_at'=>null]);
        $recepient = [];
        $time = $message->created_at;
        foreach ($members as $profileId) {
            if($profileId == $message->profile_id)
            {
                $recepient[] = ['message_id'=>$message->id, 'recepient_id'=>$profileId, 'chat_id'=>$message->chat_id, 'sent_on'=>$time, 'read_on' => $time];
            }
            else
            {
                if($message->type != 0)
                {
                    $recepient[] = ['message_id'=>$message->id, 'recepient_id'=>$profileId, 'chat_id'=>$message->chat_id, 'sent_on'=>$time, 'read_on' => $time];
                }
                else
                {
                    $recepient[] = ['message_id'=>$message->id, 'recepient_id'=>$profileId, 'chat_id'=>$message->chat_id, 'sent_on'=>$time, 'read_on' => null];
                }
            }
        }
        \DB::table('message_recepients')->insert($recepient);
        Redis::publish("chat." . $message->chat_id,json_encode($message));  
    }
}
