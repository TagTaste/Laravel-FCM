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
        $text = $otp." is your One Time Password to verify your number with TagTaste. Valid for 5 min.";
        $client = new Client();
        $response = $client->get("http://193.105.74.159/api/v3/sendsms/plain?user=".env('SMS_KAP_USERNAME')."&password=".env('SMS_KAP_PASSWORD')."&sender=".env('SMS_KAP_TEMPLATEID')."&SMSText=$text&type=longsms&GSM=91$this->phone");

        $this->model = Profile::where('id',$loggedInProfileId)->update(['otp'=>$otp]);
        $job = ((new ChangeOtp($loggedInProfileId))->onQueue('phone_verify'))->delay(Carbon::now()->addMinutes(5));

        dispatch($job);

    }
}
