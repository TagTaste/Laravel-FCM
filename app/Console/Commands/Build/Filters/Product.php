<?php

namespace App\Console\Commands\Build\Filters;

use Illuminate\Console\Command;

class Product extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:filter:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build product filter cache';

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
        \DB::table("product_filters")->delete();

        \App\PublicReviewProduct::whereNull('deleted_at')->chunk(200,function($models){
            foreach($models as $model){
                // new \App\Cached\Filter\Profile($model);
                \App\Filter\Product::addModel($model);
            }
        });
    }
}
