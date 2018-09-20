<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Crypt;

class CollabSuggestions extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $name;
    public $profileId;
    public $unsubscribeLink;
    public $settingId;
    public function __construct($name,$id,$settingId)
    {
        //
        $this->name = $name;
        $this->settingId = $settingId;
        $this->profileId = \App\Profile::where('user_id',$id)->pluck('id');
        $encryptedString = Crypt::encryptString($this->settingId."/".$this->profileId[0]."/0");
        $this->unsubscribeLink = env('APP_URL')."/settingUpdate/unsubscribe/?k=".$encryptedString;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Top trending collaborations, you should see on TagTaste.')->view('emails.collaboration-suggestions',['unsubscribeLink'=>$this->unsubscribeLink]);
    }
}
