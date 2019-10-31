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
        \App\Recipe\Profile::whereNotIn('id', [1, 44,70,165,460,5555])->whereNull('deleted_at')->chunk(200, function($profiles) use($counter) {
            foreach($profiles as $model) {
                $user_id = $model->id;
                $members = Redis::SMEMBERS("following:profile:".$model->id);
                $total_member = count($members);
                echo "profile_id: ".(int)$user_id." | ".$total_member."\n";
                if ($total_member) {
                    foreach ($members as $key => $ids) {
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
                    }
                }
                echo "*********************\n\n";
            } 
        });
    }
}
