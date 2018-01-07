<?php

namespace App\Jobs;

use App\Profile;
use Carbon\Carbon;
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
    public $countryCode;
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
        \Log::debug("Sending otp");
        $loggedInProfileId = $this->profile->id;
        $otp = mt_rand(100000, 999999);
        $client = new Client();

        $response = $client->get("http://123.63.33.43/blank/sms/user/urlsmstemp.php?username=".env('SMS_USERNAME')."&pass=".env('SMS_PASSWORD')."&senderid=BUSTER&dest_mobileno=".$this->phone."&tempid=".env('SMS_TEMPLATEID')."&F1=".$otp."&response=Y");

        $this->model = Profile::where('id',$loggedInProfileId)->update(['otp'=>$otp]);
        $job = ((new ChangeOtp($loggedInProfileId))->onQueue('phone_verify'))->delay(Carbon::now()->addMinutes(5));

        dispatch($job);

    }
}
