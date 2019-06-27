<?php

namespace App\Console\Commands\Build\Cache;

use Illuminate\Console\Command;

class PublicReviewProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:cache:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'rebuild product cache';

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
        \App\PublicReviewProduct::whereNull('deleted_at')->where('is_active',1)->chunk(200,function($models){
            foreach($models as $model){
                echo "Caching: public-review/product:" . $model->id."\n";
                $model->addToCache();
                echo "Caching: public-review/product:" . $model->id.":V2 \n\n";
                $model->addToCacheV2();
            }
        });
    }
}
