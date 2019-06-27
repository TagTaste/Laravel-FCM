<?php

namespace App\Console\Commands\Build\Cache;

use Illuminate\Console\Command;

class Share extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:cache:shared';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild Shared Cache';

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
        // \App\Shareable\Job::chunk(200,function($shares){
        //     foreach($shares as $share){
        //         $share->addToCache();
        //     }
        // });

        // \App\Shareable\Recipe::chunk(200,function($shares){
        //     foreach($shares as $share){
        //         $share->addToCache();
        //     }
        // });
        
        \App\Shareable\Collaborate::chunk(200,function($shares){
            foreach($shares as $share){
                echo "caching shared:collaborate:".$share->id." \n";
                $share->addToCache();
                echo "caching shared:collaborate:".$share->id.":V2 \n\n";
                $share->addToCacheV2();
            }
        });
        
        
        \App\Shareable\Photo::chunk(200,function($shares){
            foreach($shares as $share){
                echo "caching shared:photo:".$share->id." \n";
                $share->addToCache();
                echo "caching shared:photo:".$share->id.":V2 \n\n";
                $share->addToCacheV2();
            }
        });
        
        
    
        \App\Shareable\Shoutout::chunk(200,function($shares){
            foreach($shares as $share){
                echo "caching shared:shoutout:".$share->id." \n";
                $share->addToCache();
                echo "caching shared:shoutout:".$share->id.":V2 \n\n";
                $share->addToCacheV2();
            }
        });

        \App\Shareable\Product::chunk(200,function($shares){
            foreach($shares as $share){
                echo "caching shared:product:".$share->id." \n";
                $share->addToCache();
                echo "caching shared:product:".$share->id.":V2 \n\n";
                $share->addToCacheV2();
            }
        });
    }
}
