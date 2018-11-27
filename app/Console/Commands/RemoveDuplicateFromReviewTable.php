<?php

namespace App\Console\Commands;

use App\Collaborate;
use App\Events\DeleteFeedable;
use App\Job;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RemoveDuplicateFromReviewTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RemoveDuplicateFromReviewTable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove Duplicate From Review Table';

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
        //this run only once after that remove from kernel.php this file
//'key','value','leaf_id','question_id','tasting_header_id','collaborate_id','profile_id','batch_id','intensity'
        Collaborate\Review::orderBy('id')->chunk(100,function($models){
            foreach($models as $model){
                if(isset($model->key) && isset($model->value))
                {
                    $check = \DB::table('collaborate_tasting_user_review')->where('key','like',$model->key)->where('value','like',$model->value)
                        ->where('leaf_id',$model->leaf_id)->where('question_id',$model->question_id)->where('tasting_header_id',$model->tasting_header_id)
                        ->where('collaborate_id',$model->collaborate_id)->where('profile_id',$model->profile_id)->where('batch_id',$model->batch_id)
                        ->get();
                }
                else if(isset($model->value))
                {
                    $check = \DB::table('collaborate_tasting_user_review')->where('value','like',$model->value)
                        ->where('leaf_id',$model->leaf_id)->where('question_id',$model->question_id)->where('tasting_header_id',$model->tasting_header_id)
                        ->where('collaborate_id',$model->collaborate_id)->where('profile_id',$model->profile_id)->where('batch_id',$model->batch_id)
                       ->get();
                }
                else if(isset($model->key))
                {
                    $check = \DB::table('collaborate_tasting_user_review')->where('key','like',$model->key)
                        ->where('leaf_id',$model->leaf_id)->where('question_id',$model->question_id)->where('tasting_header_id',$model->tasting_header_id)
                        ->where('collaborate_id',$model->collaborate_id)->where('profile_id',$model->profile_id)->where('batch_id',$model->batch_id)
                        ->get();
                }
                else
                {
                    $check = \DB::table('collaborate_tasting_user_review')
                        ->where('leaf_id',$model->leaf_id)->where('question_id',$model->question_id)->where('tasting_header_id',$model->tasting_header_id)
                        ->where('collaborate_id',$model->collaborate_id)->where('profile_id',$model->profile_id)->where('batch_id',$model->batch_id)
                        ->get();

                }
                if(count($check)>1)
                {
                    $i = 0 ;
                    foreach ($check as $item)
                    {
                        if($i == 1)
                        {
                            \DB::table('collaborate_tasting_user_review')
                                ->where('leaf_id',$model->leaf_id)->where('question_id',$model->question_id)->where('tasting_header_id',$model->tasting_header_id)
                                ->where('collaborate_id',$model->collaborate_id)->where('profile_id',$model->profile_id)->where('batch_id',$model->batch_id)
                                ->delete();
                        }
                        $i++;
                        echo $item->id."\n";
                    }
                }
            }
        });

    }
}
