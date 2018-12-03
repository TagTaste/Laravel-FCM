<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendFeatureMessage
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $id;
    public $data;
    public $loggedInProfileId;
    public $loggedInProfile;
    public function __construct($data,$id,$loggedInProfile)
    {
        //
        $this->data = $data;
        $this->id = $id;
        $this->loggedInProfileId = $loggedInProfile->id;
        $this->loggedInProfile = $loggedInProfile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $existingChats = \App\V1\Chat::open($this->id,$this->loggedInProfileId);
        if(!is_null($existingChats) && $existingChats->count() > 0)
        {
            $this->model = $existingChats;
            $chatId = $this->model->id;
        }
        else
        {
            $chatInfo = ['name'=>null,'profile_id'=>$this->loggedInProfileId,'image'=>null, 'chat_type'=>1];
            $this->model = \App\V1\Chat::create($chatInfo);
            $now = \Carbon\Carbon::now()->toDateTimeString();
            $member = [];
            $chatId = $this->model->id;
            //for add login profile id in member model
            $member[] = ['chat_id'=>$chatId,'profile_id'=>$this->loggedInProfileId, 'created_at'=>$now,'updated_at'=>$now,'is_admin'=>1,'is_single'=>1];
            $member[] = ['chat_id'=>$chatId,'profile_id'=>$this->id, 'created_at'=>$now,'updated_at'=>$now,'is_admin'=>0,'is_single'=>1];

            $this->model->members()->insert($member);
        }

        $messageInfo = ['chat_id'=>$chatId,'profile_id'=>$this->loggedInProfileId,'message'=>$this->data['message']];
        $this->model = \App\V1\Chat\Message::create($messageInfo);
        event(new \App\Events\Chat\V1\Message($this->model,$this->loggedInProfile));
                 
    }
}
