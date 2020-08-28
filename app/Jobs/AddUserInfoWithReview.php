<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AddUserInfoWithReview implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $profileId;
    public $productId;
    public function __construct($productId, $profileId)
    {
        $this->profileId = $profileId;
        $this->productId = $productId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $profile = \App\Recipe\Profile::where('id',$this->profileId)->get();
        if(isset($profile) && !empty($profile)) {
            \DB::table('public_product_user_info')
                    ->insert([
                        'profile_id'=>$this->profileId,
                        'product_id'=>$this->productId,
                        'hometown'=>$profile->hometown,
                        'city'=>$profile->city,
                        'gender'=>$profile->gender,
                        'ageGroup'=>$profile->ageRange,
                        'designation'=>$profile->designation
                        ]);
        }
    }
}
