<?php

namespace App\Console\Commands\Build\Cache;

use App\Shareable\Collaborate;
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
        Collaborate::chunk(200,function($shares){
            foreach($shares as $share){
                $share->addToCache();
            }
        });
        
        // \App\Shareable\Job::chunk(200,function($shares){
        //     foreach($shares as $share){
        //         $share->addToCache();
        //     }
        // });
        
        \App\Shareable\Photo::chunk(200,function($shares){
            foreach($shares as $share){
                $share->addToCache();
            }
        });
        
        // \App\Shareable\Recipe::chunk(200,function($shares){
        //     foreach($shares as $share){
        //         $share->addToCache();
        //     }
        // });
    
        \App\Shareable\Shoutout::chunk(200,function($shares){
            foreach($shares as $share){
                $share->addToCache();
            }
        });

        \App\Shareable\Product::chunk(200,function($shares){
            foreach($shares as $share){
                echo "caching shared:product:".$share->id." \n";
                $share->addToCache();
            }
        });

        \App\Shareable\Polling::chunk(200,function($shares){
            foreach($shares as $share){
                echo "caching shared:polling:".$share->id." \n";
                $share->addToCache();
            }
        });
    }
}
