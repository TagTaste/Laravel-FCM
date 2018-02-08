<?php

namespace App\Console\Commands\Build\Cache;

use App\Subscriber;
use Illuminate\Console\Command;

class Following extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:cache:following';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'rebuild following cache.';

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
        Subscriber::where("channel_name","like","public.%")->chunk(200,function($subscribers){
            
            foreach($subscribers as $model){
                $channelOwnerProfileId = explode(".",$model->channel_name);
                $channelOwnerProfileId = last($channelOwnerProfileId);
                if($model->profile_id == $channelOwnerProfileId){
                    continue;
                }
                $key = "following:profile:" . $model->profile_id;
                echo 'updating ' . $key . "\n";
                if(!is_null($model->deleted_at)){
                    \Redis::sAdd($key, $channelOwnerProfileId);
                } else {
                    \Redis::sRem($key,$channelOwnerProfileId);
                }
            }
        });
    
        \DB::table("companies")->join("users",'users.id','=','companies.user_id')
            ->select("companies.id")
            ->whereNull('users.deleted_at')
            ->whereNull("companies.deleted_at")->orderBy('id')->chunk(200,function($companies){
            $channelNames = [];
            foreach($companies->pluck('id')->toArray() as $id){
                $channelNames[] = "company.public." . $id;
            }
            Subscriber::whereNull("deleted_at")->whereIn("channel_name",$channelNames)
                ->chunk(200,function($subscribers){
    
                foreach($subscribers as $model){
                    $channelOwnerProfileId = explode(".",$model->channel_name);
                    $channelOwnerProfileId = last($channelOwnerProfileId);
                    //adding profile id check for company would not make sense.
                    //company.public.3 => company id 3
                    // company id != profile id
                    $key = "following:profile:" . $model->profile_id;
                    echo 'updating ' . $key . "\n";
                    if(!is_null($model->deleted_at)){
                        \Redis::sAdd($key, "company.".$channelOwnerProfileId);
                    } else {
                        \Redis::sRem($key, "company.".$channelOwnerProfileId);
                    }
                    
                }
            });
        });
    }
}
