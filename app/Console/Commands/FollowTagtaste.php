<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class FollowTagtaste extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'follow:company';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        //111, 137, 322
        //1,6,7
        $companies = \App\Company::whereIn('id',[111,137,322])->get();
        $hook = env('SLACK_HOOK');
        $client =  new \GuzzleHttp\Client();
        \App\User::whereNull('deleted_at')->chunk(100,function($users) use ($companies){
            foreach ($users as $user){
                if(isset($user->profile->id))
                {
                    foreach ($companies as $company){
                        $model = $user->completeProfile->subscribeNetworkOf($company);
                        if(!$model)
                            break;
                        //companies the logged in user is following
                        \Redis::sAdd("following:profile:" . $user->profile->id, "company.$company->id");

                        //profiles that are following $channelOwner
                        \Redis::sAdd("followers:company:" . $company->id, $user->profile->id);
                    }
                }
            }
        });
        $client->request('POST', $hook,
            [
                'json' =>
                    [
                        "channel" => "@tushar",
                        "username" => "ramukaka",
                        "icon_emoji" => ":older_man::skin-tone-3:",
                        "text" => "Every profile on live server subscribed to ".$companies[0]->name.",".$companies[1]->name." and ".$companies[2]->name
                    ]
            ]);
    }
}
