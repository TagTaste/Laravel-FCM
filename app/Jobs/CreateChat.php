<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use App\V1\Chat\Member;
use App\V1\Chat;

class CreateChat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $loggedInProfileId;
    protected $profileId;
    protected $message;
    protected $preview;

    public function __construct($loggedInProfileId, $profileId, $message, $preview)
    {
        $this->loggedInProfileId = $loggedInProfileId;
        $this->profileId = $profileId;
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
        Log::info("Creating chat between {$this->loggedInProfileId} and {$this->profileId}");

        $chat = Chat::open($this->loggedInProfileId, $this->profileId);

        if (!$chat) {
            $chat = Chat::create(['profile_id' => $this->loggedInProfileId, 'chat_type' => 1]);
            $input = [
                ['chat_id' => $chat->id, 'profile_id' => $this->loggedInProfileId, 'is_admin' => 1],
                ['chat_id' => $chat->id, 'profile_id' => $this->profileId, 'is_admin' => 0]
            ];
            Member::insert($input);
        }
        Log::info("New chat created with ID: {$chat->id}");

        // Dispatch SendMessage job after creating the chat
        dispatch(new SendMessage([
            'message' => $this->message,
            'profile_id' => $this->loggedInProfileId,
            'preview' => $this->preview,
            'chat_id' => $chat->id
        ]));
    }
}
