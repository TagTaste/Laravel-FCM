<?php
namespace App\Notifications;
use App\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendInvitation extends Mailable
{
    use Queueable, SerializesModels;
    protected $user;
    protected $email;
    protected $inviteCode;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$email)
    {
        $this->user = $user;
        $this->user->inviteCode = $this->generate();
        Invitation::create(['invite_code'=>$this->user->inviteCode,'name'=>$user->name,'email'=>$email,'accepted'=>0, 'accepted_at'=>null]);

    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view("invitation.invitation")->with([
            "inviteCode" => $this->user->inviteCode,
        ]);
    }

    public static function generate()
    {
        $exists = true;
        while ($exists) {
            $code = str_random(15);
            $check = Invitation::where('invite_code', $code)->first();
            if( ! $check){
                $exists = false;
            }
        }
        return $code;
    }
}