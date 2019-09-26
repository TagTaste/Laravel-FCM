<?php

namespace App\Jobs;

use App\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
class SendConnectionInvite implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $invitation;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user,$invitation)
    {
        $this->user = $user;
        $this->invitation = $invitation;
    }
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = ["senderName"=>$this->user->name,
            "inviteCode" => $this->invitation['invite_code'],"mailCode"=>$this->invitation['mail_code'],'message'=>$this->invitation['message']];
        \Mail::send('email.connect', $data, function($message)
        {
            $message->to($this->invitation['email'], $this->user->name)->subject('Welcome!');
        });
    }
}
