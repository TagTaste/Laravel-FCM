<?php

namespace App\Console\Commands\Build\Cache;

use Illuminate\Console\Command;
use App\Helper;
use Illuminate\Support\Facades\Redis;

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
        \App\Recipe\Profile::where('id',2049)->whereNull('deleted_at')->chunk(200,function($profiles){
            foreach($profiles as $model){
                echo "updating profile:small:".$model->id.":V2 \n";
                $model->addToCacheV2();
                
                $keySmall = "profile:small:" . $model->id;
                echo 'updating ' . $keySmall . "\n";
                Redis::set($keySmall, $model->toJson());
            }
        });
    }
}
