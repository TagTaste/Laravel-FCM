<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class iOSAppLink
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
        \Log::info("job");
        \Mail::send('emails.iOS-App-Link', ['userName'=>$this->username], function($message)
        {
            $message->to($this->useremail, $this->username)->subject('TagTaste has an iOS application!');
        });
    }
}