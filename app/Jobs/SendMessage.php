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
        if(count($this->profileIds))
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
                Message::create(['message'=>$this->message, 'profile_id'=>$this->loggedInProfileId, 'preview'=>$this->preview, 'chat_id'=>$chat->id]);
                Log::info("Message sent successfully.");
            }
        }
        if(count($this->chatIds))
        {
            foreach ($this->chatIds as $chatId){
                $isMember = Member::withTrashed()->where('chat_id',$chatId)->where('profile_id',$this->loggedInProfileId)->whereNull('exited_on')->exists();
                if($isMember)
                {
                    Log::info("Sending message: {$this->message} to chat ID: {$chatId}");
                    Message::create(['message'=>$this->message, 'profile_id'=>$this->loggedInProfileId, 'preview'=>$this->preview, 'chat_id'=>$chatId]);
                    Log::info("Message sent successfully.");
                }
            }
        }
    }
}
