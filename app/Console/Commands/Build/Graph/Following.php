<?php

namespace App\Console\Commands\Build\Graph;

use App\Subscriber;
use Illuminate\Console\Command;

class Following extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:graph:following';

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
        Subscriber::join("profiles",'profiles.id','=','subscribers.profile_id')
            ->whereNull('profiles.deleted_at')
            ->whereNull('subscribers.deleted_at')
            ->select('profile_id', 'channel_name')
            ->chunk(200, function ($subscribers) {
                echo "************* Count " . $subscribers->count() . "\n\n";
                $counter = 1;
                foreach ($subscribers as $model) {
                    $user_id = (int)$model->profile_id;

                    $channel = explode(".",$model->channel_name);
                    if ($channel[0] === "company") {
                        continue;
                    }
                    
                    $channel_owner_profile_id = last($channel);
                    if ($model->profile_id == $channel_owner_profile_id) {
                        continue;
                    }
                    
                    $following_id = (int)$channel_owner_profile_id;
                    $user_profile = \App\Neo4j\User::where('profile_id', $user_id)->first();
                    $following_profile = \App\Neo4j\User::where('profile_id', $following_id)->first();
                    if ($user_profile && $following_profile) {
                        $is_user_following = $following_profile->follows->where('profile_id', $user_id)->first();
                        if (!$is_user_following) {
                            echo $counter." | User: ".$user_id." is following id: ".$following_id." not associated. \n";
                            $relation = $following_profile->follows()->attach($user_profile);
                            $relation->following = 1;
                            $relation->save();
                        } else {
                            $relation = $following_profile->follows()->edge($user_profile);
                            $relation->following = 1;
                            $relation->save();
                            echo $counter." | User: ".$user_id." is following id: ".$following_id." already associated. \n";
                        }
                    } else {
                        echo $counter." | Either User : ".$user_id." not exist or following id: ".$following_id." not exist. \n";
                    }
                    $counter = $counter + 1;
                }
                sleep(2);
            });
    }
}
