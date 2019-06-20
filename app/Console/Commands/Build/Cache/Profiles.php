<?php

namespace App\Console\Commands\Build\Cache;

use Illuminate\Console\Command;
use App\Helper;

class Profiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:cache:profiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuilds profile cache';

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
        \App\Recipe\Profile::whereNull('deleted_at')->chunk(200,function($profiles){
            foreach($profiles as $model){
                $keyRequired = [
                    'id',
                    'user_id',
                    'name',
                    'designation',
                    'handle',
                    'tagline',
                    'image_meta',
                    'isFollowing'
                ];
                $data = Helper::camel_case_keys(
                    array_intersect_key(
                        $model->toArray(), 
                        array_flip($keyRequired)
                    )
                );
                $key = "profile:small:" . $model->id.":V2";
                echo 'updating ' . $key . "\n";
                \Redis::set($key,json_encode($data));
                
                $keySmall = "profile:small:" . $model->id;
                echo 'updating ' . $keySmall . "\n";
                \Redis::set($keySmall, $model->toJson());
            }
        });
    }
}
