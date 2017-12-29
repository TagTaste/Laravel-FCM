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
    public function __construct($phone,$countryCode, Profile $profile)
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
        \Log::debug("otp is $otp");
        $client = new Client();

        $response = $client->get("http://websmsapp.in/api/mt/SendSMS?APIKey=".env('TOP10SMS_API_KEY')."&senderid=".env('TOP10SMS_SENDERID')."&channel=Trans&DCS=0&flashsms=0&number=".$this->countryCode.$this->phone."&text=".$otp." is your One Time Password to verify your number with TagTaste. Valid for 5 min&route=2");

        $this->model = Profile::where('id',$loggedInProfileId)->update(['otp'=>$otp]);
        $job = ((new ChangeOtp($loggedInProfileId))->onQueue('phone_verify'))->delay(Carbon::now()->addMinutes(5));

        dispatch($job);

    }
}
