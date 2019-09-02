<?php

namespace App\Console\Commands\Build\Graph\Build;

use App\Subscriber;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

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
         $counter = 1;
        \App\Recipe\Profile::whereNull('deleted_at')->chunk(200, function($profiles) use($counter) {
            foreach($profiles as $model) {
                $user_id = $model->id;
                // echo $user_id." | ".Redis::SCARD("following:profile:".$model->id)."\n\n";
                $members = Redis::SMEMBERS("following:profile:".$model->id);
                $total_member = count($members);
                echo "profile_id: ".(int)$user_id." | ".$total_member."\n";
                if ($total_member) {
                    foreach ($members as $key => $ids) {
                        // if ($key < 3482) {
                        //     echo "profile_id: ".(int)$user_id." | (".($key+1)."/".$total_member."\n";
                        // } else {
                            if (strpos($ids, 'company') !== false) {
                                $company_detail = explode(".",$ids);
                                if (count($company_detail) == 2) {
                                    $company_id = (int)$company_detail[1];
                                    echo "profile_id: ".(int)$user_id." | (".($key+1)."/".$total_member.") company following_id: ".(int)$company_id."\n";
                                    Subscriber::followCompanySuggestion($user_id, $company_id);
                                }
                            } else {
                                $following_id = (int)$ids;
                                echo "profile_id: ".(int)$user_id." | (".($key+1)."/".$total_member.") user following_id: ".(int)$following_id."\n";
                                Subscriber::followProfileSuggestion($user_id, $following_id);
                            }
                        // }
                        
                    }
                }
                echo "*********************\n\n";
            } 
        });
        // $subscribers = Subscriber::join("profiles",'profiles.id','=','subscribers.profile_id')
        //     ->whereNull('profiles.deleted_at')
        //     ->whereNull('subscribers.deleted_at')
        //     ->select('subscribers.id', 'profile_id', 'channel_name')
        //     ->skip(233000)
        //     ->limit(50000)
        //     ->get();

        // echo "************* Count " . $subscribers->count() . "\n\n";
        // $counter = 1;
        // foreach ($subscribers as $model) {
        //     echo "\n".$counter."| id: ".$model['id']." | profile_id: ".(int)$model['profile_id']." | channel: ".$model['channel_name']."\n";
        //     $model->followSuggestion();
        //     $counter = $counter + 1;
        // }


        // Subscriber::join("profiles",'profiles.id','=','subscribers.profile_id')
        //     ->whereNull('profiles.deleted_at')
        //     ->whereNull('subscribers.deleted_at')
        //     ->select('subscribers.id', 'profile_id', 'channel_name')
        //     ->chunk(50, function ($subscribers) {
        //         echo "sleep for 1 second.";
        //         sleep(1);
        //         echo "************* Count " . $subscribers->count() . "\n\n";
        //         $counter = 1;
        //         foreach ($subscribers as $model) {
        //             echo "\n".$counter."| id: ".$model['id']." | profile_id: ".(int)$model['profile_id']." | channel: ".$model['channel_name']."\n";
        //             $model->followSuggestion();
        //             $counter = $counter + 1;
        //         }
        //     });
    }
}
