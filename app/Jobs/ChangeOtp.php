<?php

namespace App\Jobs;

use App\Profile;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ChangeOtp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $loggedInProfileId;
    public function __construct($loggedInProfileId)
    {
        $this->loggedInProfileId = $loggedInProfileId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Profile::where('id',$this->loggedInProfileId)->update(['otp'=>null]);
    }
}
