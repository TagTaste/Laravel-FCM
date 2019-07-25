<?php

namespace App\Console\Commands\Build\Graph\Build;

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


        Subscriber::join("profiles",'profiles.id','=','subscribers.profile_id')
            ->whereNull('profiles.deleted_at')
            ->whereNull('subscribers.deleted_at')
            ->select('subscribers.id', 'profile_id', 'channel_name')
            ->chunk(50, function ($subscribers) {
                echo "sleep for 1 second.";
                sleep(1);
                echo "************* Count " . $subscribers->count() . "\n\n";
                $counter = 1;
                foreach ($subscribers as $model) {
                    echo "\n".$counter."| id: ".$model['id']." | profile_id: ".(int)$model['profile_id']." | channel: ".$model['channel_name']."\n";
                    $model->followSuggestion();
                    $counter = $counter + 1;
                }
            });
    }
}
