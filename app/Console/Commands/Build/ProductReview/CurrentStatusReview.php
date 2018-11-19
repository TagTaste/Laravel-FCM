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
        \DB::table('collaborate_batches_assign')->orderBy('collaborate_id')->chunk(100, function ($models) {
            foreach ($models as $model) {
                \Redis::set("current_status:batch:$model->batch_id:profile:$model->profile_id" ,0);
                if($model->begin_tasting == 1)
                {
                    \Redis::set("current_status:batch:$model->batch_id:profile:$model->profile_id" ,1);
                }
                $currentstatus = \DB::table('collaborate_tasting_user_review')->where('batch_id',$model->batch_id)
                    ->where('profile_id',$model->profile_id)->orderBy('id', 'desc')->first();
                echo "profile id ".$model->profile_id." batch id ".$model->batch_id." current status .".$currentstatus;
                if(isset($currentStatus))
                {
                    if($currentStatus->current_status == 3)
                    {
                        \Redis::set("current_status:batch:$model->batch_id:profile:$model->profile_id" ,3);
                    }
                    else
                    {
                        \Redis::set("current_status:batch:$model->batch_id:profile:$model->profile_id" ,2);
                    }
                }
                echo "batch_id ".$model->batch_id." profile_id ".$model->profile_id ."current status ".\Redis::get("current_status:batch:$model->batch_id:profile:$model->profile_id")."\n";
            }
        });;
    }
}
