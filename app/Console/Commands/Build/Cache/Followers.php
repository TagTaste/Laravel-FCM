<?php

namespace App\Console\Commands\Build\Cache;

use App\Subscriber;
use Illuminate\Console\Command;

class Followers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:cache:followers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'rebuild followers cache.';

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
        Subscriber::whereNull('deleted_at')->chunk(200,function($subscribers){
            
            foreach($subscribers as $model){
                $channel = explode(".",$model->channel_name);
                $channelOwnerProfileId = last($channel);
                if($model->profile_id == $channelOwnerProfileId){
                    continue;
                }
                $key = "followers:";
                $key .= $channel[0] === 'company' ? 'company:' : 'profile:';
                $key .= $channelOwnerProfileId;
                \Redis::sAdd($key, $model->profile_id);
            }
        });
    }
}
