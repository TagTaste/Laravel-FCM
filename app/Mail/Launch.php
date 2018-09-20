<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Crypt;

class Launch extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $email;
    public $encryptedString;
    public $unsubscribe_link;

    public function __construct($email)
    {
        $this->email = $email;
        $this->encryptedString = Crypt::encryptString($this->email);
        $this->unsubscribe_link = env('APP_URL')."/api/settingUpdate/unsubscribe/?k=".$this->encryptedString;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Invitation to join TagTaste')->view('emails.Invitation-beta',['unsubscribe_link'=>$this->unsubscribe_link]);
    }
}
