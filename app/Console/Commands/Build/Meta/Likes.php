<?php

namespace App\Console\Commands\Build\Meta;

use App\Application;
use App\Collaboration\Collaborator;
use App\CollaborationLike;
use Illuminate\Console\Command;

class Likes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:meta:like';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild Like Cache';

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
        \DB::table("collaborate_share_likes")->orderBy('collaborate_share_id')->chunk(100,function($models){
            foreach($models as $model){
                $key = "meta:collaborateShare:likes:" . $model->collaborate_share_id;
                \Redis::sAdd($key,$model->profile_id);
            }
        });
    
        \DB::table("collaboration_likes")->orderBy('collaboration_id')->chunk(100,function($models){
            foreach($models as $model){
                $key = "meta:collaborate:likes:" . $model->collaboration_id;
                \Redis::sAdd($key,$model->profile_id);
            }
        });
    
        \DB::table("photo_likes")->orderBy('photo_id')->chunk(100,function($models){
            foreach($models as $model){
                $key = "meta:photo:likes:" . $model->photo_id;
                \Redis::sAdd($key,$model->profile_id);
            }
        });
    
        \DB::table("photo_share_likes")->orderBy('photo_share_id')->chunk(100,function($models){
            foreach($models as $model){
                $key = "meta:photoShare:likes:" . $model->photo_share_id;
                \Redis::sAdd($key,$model->profile_id);
            }
        });
    
        \DB::table("recipe_likes")->orderBy('recipe_id')->chunk(100,function($models){
            foreach($models as $model){
                $key = "meta:recipe:likes:" . $model->recipe_id;
                \Redis::sAdd($key,$model->profile_id);
            }
        });
    
        \DB::table("recipe_share_likes")->orderBy('recipe_share_id')->chunk(100,function($models){
            foreach($models as $model){
                $key = "meta:recipeShare:likes:" . $model->recipe_share_id;
                \Redis::sAdd($key,$model->profile_id);
            }
        });
    
        \DB::table("shoutout_likes")->orderBy('shoutout_id')->chunk(100,function($models){
            foreach($models as $model){
                $key = "meta:shoutout:likes:" . $model->shoutout_id;
                \Redis::sAdd($key,$model->profile_id);
            }
        });
        \DB::table("shoutout_share_likes")->orderby('shoutout_share_id')->chunk(100,function($models){
            foreach($models as $model){
                $key = "meta:shoutoutShare:likes:" . $model->shoutout_share_id;
                \Redis::sAdd($key,$model->profile_id);
            }
        });

        \DB::table("polling_share_likes")->orderby('poll_share_id')->chunk(100,function($models){
            foreach($models as $model){
                $key = "meta:pollingShare:likes:" . $model->poll_share_id;
                \Redis::sAdd($key,$model->profile_id);
            }
        });

        \DB::table("polling_likes")->orderBy('poll_id')->chunk(100,function($models){
            foreach($models as $model){
                $key = "meta:polling:likes:" . $model->poll_id;
                \Redis::sAdd($key,$model->profile_id);
            }
        });
        
    }
    
}
