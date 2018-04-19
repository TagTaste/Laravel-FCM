<?php

namespace App\Jobs;

use App\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
class SendInvitation implements ShouldQueue
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
        if(isset($this->invitation['name']))
        {
            $data = ["senderName"=>$this->user->name,"senderImage"=>$this->user->profile->imageUrl,"receiverName"=>$this->invitation['name'],
                "mailCode"=>$this->invitation['mail_code'],'message'=>$this->invitation['message']];
            \Mail::send('emails.invitation', $data, function($message)
            {
                $message->to($this->invitation['email'], $this->user->name)->subject($this->user->name.' has invited you to join TagTaste');
            });
        }
    }
}