<?php

namespace App\Console\Commands\Build\Cache;

use App\Collaborate;
use Illuminate\Console\Command;

class CurrentStatusReview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:cache:current_status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'rebuild current status of product review according to user cache';

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
        \DB::table('collaborate_batches_assign')->chunk(100, function ($models) {
            foreach ($models as $model) {
                \Redis::set("current_status:batch:$model->batch_id:profile:$model->profile_id:" ,0);
                if($model->begin_tasting == 1)
                {
                    \Redis::set("current_status:batch:$model->batch_id:profile:$model->profile_id:" ,1);
                }
                $currentstatus = \DB::table('collaborate_tasting_user_review')->where('batch_id',$model->batch_id)
                    ->where('profile_id',$model->profile_id)->orderBy('id', 'desc')->first();
                if(isset($currentStatus))
                {
                    if($currentStatus->current_status == 3)
                    {
                        \Redis::set("current_status:batch:$model->batch_id:profile:$model->profile_id:" ,3);
                    }
                    \Redis::set("current_status:batch:$model->batch_id:profile:$model->profile_id:" ,2);
                }
            }
        });;
    }
}
