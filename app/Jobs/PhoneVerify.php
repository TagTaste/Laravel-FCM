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
        $loggedInProfileId = $this->profile->id;
        Profile::where('id',$loggedInProfileId)->update(['otp'=>null]);
        //$job = ((new ChangeOtp($loggedInProfileId))->onQueue('phone_verify'))->delay(Carbon::now()->addMinutes(5));

        //dispatch($job);

    }
}
