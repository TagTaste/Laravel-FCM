<?php

namespace App\Console\Commands;

use App\Collaborate;
use App\Company;
use App\Photo;
use App\Profile;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProgressiveImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'progressive:image';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'all image move into image meta with url';

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
        Profile::whereNull('deleted_at')->orderBy('id')->chunk(100, function ($models) {
            foreach ($models as $model) {
                if(isset($model->image) && !is_null($model->image))
                {
                    $image = $model->image;
                    $image = str_replace('https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/https%3A//s3.ap-south-1.amazonaws.com/static3.tagtaste.com','https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com',$image);
                    \Log::info($image);
                    $imageMeta = [];
                    $imageMeta['original_photo'] = $image;
                    $imageMeta['tiny_photo'] = $image;
                    $imageMeta['meta'] = ['tiny_photo'=>$image];
                    $imageMeta = json_encode($imageMeta,true);
                    $model->update(['image_meta'=> $imageMeta,'image'=>$image]);
                }
//                if(isset($model->hero_image) && !is_null($model->hero_image))
//                {
//                    $heroImage = $model->hero_image;
//                    $heroImage = str_replace('https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/https%3A//s3.ap-south-1.amazonaws.com/static3.tagtaste.com','https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com',$heroImage);
//                    \Log::info($heroImage);
//                    $imageMeta = [];
//                    $imageMeta['original_photo'] = $heroImage;
//                    $imageMeta['tiny_photo'] = $heroImage;
//                    $imageMeta['meta'] = ['tiny_photo'=>$heroImage];
//                    $imageMeta = json_encode($imageMeta,true);
//                    $model->update(['hero_image_meta'=> $imageMeta,'hero_image'=>$heroImage]);
//                }
                echo "profile id ".$model->id ."\n";
                $model->addToCache();
            }
        });

        //        Photo::whereNull('deleted_at')->orderBy('id')->chunk(100, function ($models) {
//            foreach ($models as $model) {
//                if(isset($model->photoUrl) && !is_null($model->photoUrl))
//                {
//                    $imageMeta = [];
//                    $imageMeta['original_photo'] = \Storage::url($model->photoUrl);
//                    $imageMeta['tiny_photo'] = \Storage::url($model->photoUrl);
//                    $imageMeta['meta'] = ['tiny_photo'=>\Storage::url($model->photoUrl)];
//
//                    $imageMeta = json_encode($imageMeta,true);
//                    $model->update(['image_meta'=> $imageMeta]);
//                }
//                echo "photo id ".$model->id ."\n";
//
//                $model->addToCache();
//
//            }

//        Company::whereNull('deleted_at')->orderBy('id')->chunk(100, function ($models) {
//            foreach ($models as $model) {
//                if(isset($model->logo) && !is_null($model->logo))
//                {
//                    $imageMeta = [];
//                    $imageMeta['original_photo'] = $model->logo;
//                    $imageMeta['tiny_photo'] = $model->logo;
//                    $imageMeta['meta'] = ['tiny_photo'=>$model->logo];
//                    $imageMeta = json_encode($imageMeta,true);
//                    $model->update(['logo_meta'=> $imageMeta,'logo'=>$model->logo]);
//                }
//                if(isset($model->hero_image) && !is_null($model->hero_image))
//                {
//                    $imageMeta = [];
//                    $imageMeta['original_photo'] = $model->hero_image;
//                    $imageMeta['tiny_photo'] = $model->hero_image;
//                    $imageMeta['meta'] = ['tiny_photo'=>\Storage::url($model->hero_image)];
//                    $imageMeta = json_encode($imageMeta,true);
//                    $model->update(['hero_image_meta'=> $imageMeta,'hero_image'=>\Storage::url($model->hero_image)]);
//                }
//                echo "company id ".$model->id ."\n";
//
//                $model->addToCache();
//
//            }
//        });
        Photo::whereNull('deleted_at')->orderBy('id')->chunk(100, function ($models) {
            foreach ($models as $model) {
                if(isset($model->photoUrl) && !is_null($model->photoUrl))
                {
                    $model->update(['file'=>\Storage::url($model->photoUrl)]);
                }
                echo "photo id ".$model->id ."\n";

                $model->addToCache();

            }
        });
//        Company\Gallery::whereNull('deleted_at')->orderBy('id')->chunk(100, function ($models) {
//            foreach ($models as $model) {
//                if(isset($model->imageUrl) && !is_null($model->imageUrl))
//                {
//                    $imageMeta = [];
//                    $imageMeta['original_photo'] = \Storage::url($model->imageUrl);
//                    $imageMeta['tiny_photo'] = \Storage::url($model->imageUrl);
//                    $imageMeta['meta'] = ['tiny_photo'=>\Storage::url($model->imageUrl)];
//
//                    $imageMeta = json_encode($imageMeta,true);
//                    echo "gallery id ".$model->id ."\n";
//
//                    $model->update(['image_meta'=> $imageMeta]);
//                }
//            }
//        });
//
//        Company\Product::whereNull('deleted_at')->orderBy('id')->chunk(100, function ($models) {
//            foreach ($models as $model) {
//                if(isset($model->imageUrl) && !is_null($model->imageUrl))
//                {
//                    $imageMeta = [];
//                    $imageMeta['original_photo'] = \Storage::url($model->imageUrl);
//                    $imageMeta['tiny_photo'] = \Storage::url($model->imageUrl);
//                    $imageMeta['meta'] = ['tiny_photo'=>\Storage::url($model->imageUrl)];
//
//                    $imageMeta = json_encode($imageMeta,true);
//                    echo "Product id ".$model->id ."\n";
//
//                    $model->update(['image_meta'=> $imageMeta]);
//                }
//            }
//        });
//
//        Collaborate::orderBy('id')->chunk(100, function ($models) {
//            foreach ($models as $model) {
//                if(count($model->images))
//                {
//                    $imagesMeta = [];
//                    foreach ($model->images as $image)
//                    {
//                        if(!isset($image))
//                        {
//                            continue;
//                        }
//                        $imageMeta = [];
//                        $imageMeta['original_photo'] = $image;
//                        $imageMeta['tiny_photo'] = $image;
//                        $imageMeta['meta'] = ['tiny_photo'=>$image];
//                        $imagesMeta[] = $imageMeta;
//                    }
//                    $images = json_encode($imagesMeta,true);
//                    $model->update(['images_meta'=> $images]);
//                }
//                echo "Collaborate id ".$model->id ."\n";
//
//                $model->addToCache();
//
//            }
//        });
    }
}
