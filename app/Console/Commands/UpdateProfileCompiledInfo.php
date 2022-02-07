<?php

namespace App\Console\Commands;

use App\Profile;
use App\ProfileCompiledInfo;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class UpdateProfileCompiledInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profile_compiled_detail:update';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update profile compiled detail';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $timestamp = Carbon::now();

        Profile::whereNull('deleted_at')->chunk(200, function($profiles) use ($timestamp) {
            foreach($profiles as $model) {
                $data = array(
                    'profile_id' => $model->id,
                    'shoutout_post' => $model->shoutoutPostCount,
                    'shoutout_shared_post' => $model->shoutoutSharePostCount,
                    'collaborate_post' => $model->collaboratePostCount,
                    'collaborate_share_post' => $model->collaborateSharePostCount,
                    'photo_post' => $model->photoPostCount,
                    'photo_share_post' => $model->photoSharePostCount,
                    'poll_post' => $model->pollingPostCount,
                    'poll_share_post' => $model->pollingSharePostCount,
                    'product_share_post' => $model->productSharePostCount,
                    'follower_count' => $model->followerProfiles['count'],
                    'total_post_count' => $model->totalPostCount,
                    'private_review_count' => $model->privateReviewCount,
                    'public_review_count' => $model->reviewCount,
                    'survey_count' => $model->surveyCount,
                    'amount' => $model->amount
                );

                $profile_compiled_info = ProfileCompiledInfo::where('profile_id', $model->id)->first();
                if (is_null($profile_compiled_info)) {
                    $data['profile_id'] = $model->id;
                    $data['created_at'] = $timestamp;
                    $data['updated_at'] = $timestamp;
                    ProfileCompiledInfo::insert($data);
                    echo "Profile compiled data inserted for ".$model->id." \n";
                } else {
                    $data['updated_at'] = $timestamp;
                    ProfileCompiledInfo::where('profile_id', $model->id)->update($data);
                    echo "Profile compiled data updated for ".$model->id." \n";
                }
            }
        });
    }
}
