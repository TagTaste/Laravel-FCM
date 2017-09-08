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
    protected $email;
    protected $inviteUser;
    protected $inviteCode;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user,$inviteUser,$email)
    {
        $this->user = $user;
        $this->inviteUser = $inviteUser;
        $this->email = $email;
        $this->inviteCode = str_random(15);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = ["userName"=>$this->user->name,
            "inviteCode" => $this->inviteCode];
        \Mail::send('invitation.invitation', $data, function($message)
        {
            $message->to($this->email, $this->user->name)->subject('Welcome!');
        });
        Invitation::create(['invite_code'=>$this->inviteCode,'name'=>$this->inviteUser->name,'email'=>$this->email, 'accepted_at'=>null]);

    }
}
