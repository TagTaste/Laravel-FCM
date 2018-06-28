<?php

namespace App\Console\Commands;
use App\Collaborate;
use App\Company;
use App\Events\NewFeedable;
use App\Job;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class FixCollaborateImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'FixCollaborateImage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fix collab images collaboration';

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
        \DB::table("collaborates")->where('id',152)->orderBy('id')->chunk(100,function($models){
            foreach($models as $model){
                $image = [];
                $i = 1;
                if(isset($model->image1) && !is_null($model->image1))
                {
                    $image[]['image'.$i] = \Storage::url($model->image1);
                    $i++;
                }
                if(isset($model->image2) && !is_null($model->image2))
                {
                    $image[]['image'.$i] = \Storage::url($model->image2);

                    $i++;
                }
                if(isset($model->image3) && !is_null($model->image3))
                {
                    $image[]['image'.$i] = \Storage::url($model->image3);
                    $i++;
                }
                if(isset($model->image4) && !is_null($model->image4))
                {
                    $image[]['image'.$i] = \Storage::url($model->image4);
                    $i++;
                }
                if(isset($model->image5) && !is_null($model->image5))
                {
                    $image[]['image'.$i] = \Storage::url($model->image5);
                }
                $imagesArray = $model->images;
                $imagesArray = json_decode($imagesArray, true);
                foreach ($imagesArray as $image) {
                    $image[] = $image['image3'];
                    $image[] = $image['image4'];
                    break;
                }
                $images = json_encode($image,true);
   
                \DB::table('collaborates')->where('id',$model->id)->update(['images'=>$images]);
            }
        });
    }
}

