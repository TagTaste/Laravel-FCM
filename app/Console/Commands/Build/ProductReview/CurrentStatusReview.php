<?php

namespace App\Console\Commands\Build\ProductReview;

use App\Collaborate;
use Illuminate\Console\Command;

class CurrentStatusReview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:productreview:currentStatus';

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
        $models = \DB::table('collaborate_batches_assign')->get();
        foreach ($models as $model)
        {
            echo "batch id is ".$model->batch_id." profile id ".$model->profile_id."\n";
            \Redis::set("current_status:batch:$model->batch_id:profile:$model->profile_id" ,0);
            if($model->begin_tasting == 1)
            {
                \Redis::set("current_status:batch:$model->batch_id:profile:$model->profile_id" ,1);
            }
            $currentStatus = \DB::table('collaborate_tasting_user_review')->where('batch_id',$model->batch_id)
                ->where('profile_id',$model->profile_id)->first();
            if(isset($currentStatus->current_status))
            {
//                    echo "profile id ".$model->profile_id." batch id ".$model->batch_id." current status .".$currentStatus->current_status."\n";
                if($currentStatus->current_status == 3)
                {
                    \Redis::set("current_status:batch:$model->batch_id:profile:$model->profile_id" ,3);
                }
                else
                {
                    \Redis::set("current_status:batch:$model->batch_id:profile:$model->profile_id" ,2);
                }
            }
        }
    }
}
