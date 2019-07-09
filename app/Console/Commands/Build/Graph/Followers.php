<?php

namespace App\Console\Commands\Build\Graph;

use App\Subscriber;
use Illuminate\Console\Command;

class Followers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:graph:followers';

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
            ->chunk(200, function ($subscribers) {
            echo "************* Count " . $subscribers->count() . "\n\n";
            $counter = 1;
            foreach ($subscribers as $model) {
                $channel = explode(".",$model->channel_name);
                $channelOwnerProfileId = last($channel);
                if ($model->profile_id == $channelOwnerProfileId) {
                    continue;
                }

                $followedBy = $model->profile_id;
                $userId = (int)$channelOwnerProfileId;
                $user = \App\Neo4j\User::where('profileId', $userId)->first();
                $following = \App\Neo4j\User::where('profileId', $followedBy)->first();
                if (!$user) {
                    echo $counter." | User : ".$userId." not exist and followed by: ".$followedBy." exist. \n";
                } elseif(!$following) {
                    echo $counter." | User: ".$userId." exist and followed by: ".$followedBy." not exist. \n";
                } else {
                    $userFollows = $user->follows->where('profileId', $followedBy)->first();
                    if (!$userFollows) {
                        echo $counter." | User: ".$userId." is followed by: ".$followedBy." not associated. \n";
                        $relation = $user->follows()->attach($following);
                        $relation->status = 1;
                        $relation->statusValue = "follow";
                        $relation->status = 1;
                        $relation->save();
                    } else {
                        echo $counter." | User: ".$userId." is followed by: ".$followedBy." already associated. \n";
                    }
                }
                $counter = $counter + 1;
            }
        });
    }
}
