<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendFeatureMessage implements ShouldQueue
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
    public function __construct($data,$id,$loggedInProfileId)
    {
        //
        $this->data = $data;
        $this->id = $id;
        $this->loggedInProfileId = $loggedInProfileId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $existingChats = \App\Chat::open($this->id,$this->loggedInProfileId);
            \Log::info($existingChats);
            if(!is_null($existingChats) && $existingChats->count() > 0)
            {
                $this->messages[] = "chat_open";
                $this->model = $existingChats;
                 $chatId = $this->model->id;
            }
            else
               {
                         $this->data['is_single'] = 1; 
                $this->model = \App\Chat::create($this->data);
                $now = \Carbon\Carbon::now()->toDateTimeString();
                $member = [];
                $chatId = $this->model->id;
                //for add login profile id in member model
                $member[] = ['chat_id'=>$chatId,'profile_id'=>$this->loggedInProfileId, 'created_at'=>$now,'updated_at'=>$now,'is_admin'=>1,'is_single'=>1];
                $member[] = ['chat_id'=>$chatId,'profile_id'=>$this->id, 'created_at'=>$now,'updated_at'=>$now,'is_admin'=>0,'is_single'=>1];
                
                $this->model->members()->insert($member);
                }
               
                    $this->data['chat_id'] = $chatId;
                    $this->data['profile_id'] = $this->loggedInProfileId;
                    $this->model = [];
                    $this->model['data'] = \App\Chat\Message::create($this->data);
                //        $this->model = Chat\Message::where
                    $loggedInProfile = \App\profile::find($this->loggedInProfileId);
                    event(new \App\Events\Chat\Message($this->model['data'],$loggedInProfile));
                
                 
    }
}
