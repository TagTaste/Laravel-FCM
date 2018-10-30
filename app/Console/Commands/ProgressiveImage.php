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
                if(isset($model->imageUrl) && !is_null($model->imageUrl))
                {
                    $imageMeta = [];
                    $imageMeta['original_photo'] = \Storage::url($model->imageUrl);
                    $imageMeta['tiny_photo'] = \Storage::url($model->imageUrl);
                    $imageMeta['meta'] = null;
                    $imageMeta = (object)$imageMeta;
                    $model->update(['image_meta'=> $imageMeta]);
                }
                if(isset($model->heroImageUrl) && !is_null($model->heroImageUrl))
                {
                    $imageMeta = [];
                    $imageMeta['original_photo'] = \Storage::url($model->heroImageUrl);
                    $imageMeta['tiny_photo'] = \Storage::url($model->heroImageUrl);
                    $imageMeta['meta'] = null;
                    $imageMeta = json_encode($imageMeta,true);
                    $model->update(['hero_image_meta'=> $imageMeta]);
                }
                echo "profile id ".$model->id ."\n";
                $model->addToCache();
            }
        });
        Company::whereNull('deleted_at')->orderBy('id')->chunk(100, function ($models) {
            foreach ($models as $model) {
                if(isset($model->logo) && !is_null($model->logo))
                {
                    $imageMeta = [];
                    $imageMeta['original_photo'] = \Storage::url($model->logo);
                    $imageMeta['tiny_photo'] = \Storage::url($model->logo);
                    $imageMeta['meta'] = null;
                    $imageMeta = json_encode($imageMeta,true);
                    $model->update(['logo_meta'=> $imageMeta]);
                }
                if(isset($model->hero_image) && !is_null($model->hero_image))
                {
                    $imageMeta = [];
                    $imageMeta['original_photo'] = \Storage::url($model->hero_image);
                    $imageMeta['tiny_photo'] = \Storage::url($model->hero_image);
                    $imageMeta['meta'] = null;
                    $imageMeta = json_encode($imageMeta,true);
                    $model->update(['hero_image_meta'=> $imageMeta]);
                }
                echo "company id ".$model->id ."\n";

                $model->addToCache();

            }
        });
        Photo::whereNull('deleted_at')->orderBy('id')->chunk(100, function ($models) {
            foreach ($models as $model) {
                if(isset($model->photoUrl) && !is_null($model->photoUrl))
                {
                    $imageMeta = [];
                    $imageMeta['original_photo'] = \Storage::url($model->photoUrl);
                    $imageMeta['tiny_photo'] = \Storage::url($model->photoUrl);
                    if(isset($model->image_info))
                        $imageMeta['meta'] = $model->image_info;
                    else
                        $imageMeta['meta'] = null;

                    $imageMeta = json_encode($imageMeta,true);
                    $model->update(['image_meta'=> $imageMeta]);
                }
                echo "photo id ".$model->id ."\n";

                $model->addToCache();

            }
        });
        Company\Gallery::whereNull('deleted_at')->orderBy('id')->chunk(100, function ($models) {
            foreach ($models as $model) {
                if(isset($model->imageUrl) && !is_null($model->imageUrl))
                {
                    $imageMeta = [];
                    $imageMeta['original_photo'] = \Storage::url($model->imageUrl);
                    $imageMeta['tiny_photo'] = \Storage::url($model->imageUrl);
                    $imageMeta['meta'] = null;

                    $imageMeta = json_encode($imageMeta,true);
                    echo "gallery id ".$model->id ."\n";

                    $model->update(['image_meta'=> $imageMeta]);
                }
            }
        });

        Company\Product::whereNull('deleted_at')->orderBy('id')->chunk(100, function ($models) {
            foreach ($models as $model) {
                if(isset($model->imageUrl) && !is_null($model->imageUrl))
                {
                    $imageMeta = [];
                    $imageMeta['original_photo'] = \Storage::url($model->imageUrl);
                    $imageMeta['tiny_photo'] = \Storage::url($model->imageUrl);
                    $imageMeta['meta'] = null;

                    $imageMeta = json_encode($imageMeta,true);
                    echo "Product id ".$model->id ."\n";

                    $model->update(['image_meta'=> $imageMeta]);
                }
            }
        });

        Collaborate::where('state',2)->orderBy('id')->chunk(100, function ($models) {
            foreach ($models as $model) {
                if(count($model->images))
                {
                    $images = [];
                    foreach ($model->images as $image)
                    {
                        $imageMeta = [];
                        $imageMeta['original_photo'] = $image;
                        $imageMeta['tiny_photo'] = $image;
                        $imageMeta['meta'] = null;
                        $image[] = $imageMeta;
                    }
                    $images = json_encode($images,true);
                    $model->update(['image_meta'=> $images]);
                }
                echo "Collaborate id ".$model->id ."\n";

                $model->addToCache();

            }
        });
    }
}
