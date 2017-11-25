<?php

namespace App\Jobs;

use App\Profile;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PhoneVerify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $phone;
    public $profile;
    public function __construct($phone, Profile $profile)
    {
        $this->phone = $phone;
        $this->profile = $profile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $loggedInProfileId = $this->profile->id;
        $otp = mt_rand(100000, 999999);
        $client = new Client();
        $response = $client->get("http://websmsapp.in/api/mt/SendSMS?APIKey=".env('TOP10SMS_API_KEY')."&senderid=".env('TOP10SMS_SENDERID')."&channel=Trans&DCS=0&flashsms=0&number=91".$this->phone."&text=".$otp." is your One Time Password to verify your Number with TagTaste. Valid for 5 min&route=2");
        $this->model = Profile::where('id',$loggedInProfileId)->update(['otp'=>$otp]);
        sleep(300);
        Profile::where('id',$loggedInProfileId)->update(['otp'=>null]);

    }
}
