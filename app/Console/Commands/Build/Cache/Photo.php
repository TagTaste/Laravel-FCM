<?php

namespace App\Console\Commands\Build\Cache;

use Illuminate\Console\Command;
use App\Traits\GetTags;
use App\Traits\CheckTags;
use Illuminate\Support\Facades\Redis;

class Photo extends Command
{
    use GetTags, CheckTags;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:cache:photos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'rebuild photo cache';

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
        \DB::table("photos")->where('id', 2082)->orderBy('created_at')->chunk(200, function($photos){
            foreach ($photos as $photo) {
                $captionProfiles = $this->getTaggedProfilesV2($photo->caption);
                $captionDetail = $photo->caption;
                if ($captionProfiles) {
                    $captionDetail = [
                        'text' => $photo->caption,
                        'profiles' => $captionProfiles
                    ];
                }

                $image_meta = null;
                if ($photo->image_meta) {
                    $image_meta = $photo->image_meta;
                } else {
                    $images = json_decode($photo->images);
                    if (count($images) > 0) {
                        $image_meta = json_encode($images[0]);
                    }
                }

                $data = array(
                    'id'=> $photo->id,
                    'caption' => $captionDetail,
                    'deleted_at' => $photo->deleted_at,
                    'created_at' => $photo->created_at,
                    'updated_at' => $photo->updated_at,
                    'images' => json_decode($photo->images),
                    'image_meta' => $image_meta
                );
                
                foreach ($data as $key => $value) {
                    if ($key == "images" || $key == "image_meta" )
                        continue;
                    if (is_null($value) || $value == '')
                        unset($data[$key]);
                }
                echo "key = photo:".$data['id']."\n";
                Redis::set("photo:".$data['id'], json_encode($data));
            }
        });
    }
}