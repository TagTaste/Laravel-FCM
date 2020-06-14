<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ServiceInterruptionJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $useremail;
    public $username;

    public function __construct($useremail,$username)
    {
        $this->useremail = $useremail;
        $this->username = $username;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Mail::send('emails.cranberry-us', ['userName'=>$this->username], function($message)
        {
            // $path = "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/Taster's+Docket.pdf";
            $message->to($this->useremail, $this->username)->subject('Collaboration Alert - US Cranberry Recipe Rally!');
                    //->attach('https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/Pitstop+2+-+Welcome!+Cranberry.pdf');
        });
        \Mail::send('emails.cranberry-update-email', ['userName'=>$this->username], function($message)
        {
            // $path = "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/Taster's+Docket.pdf";
            $message->to($this->useremail, $this->username)->subject('Welcome to the US Cranberries Recipe Rally!')
                    ->attach('https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/Pitstop+1+-+Hi!+Dried+Berries.pdf');
        });
    }
}