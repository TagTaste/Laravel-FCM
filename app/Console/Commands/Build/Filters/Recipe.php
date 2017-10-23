<?php

namespace App\Console\Commands\Build\Filters;

use Illuminate\Console\Command;

class Recipe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:filter:recipes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build recipe filter cache';

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
        \App\Recipe::chunk(100,function($models){
            foreach($models as $model){
               // new \App\Cached\Filter\Profile($model);
                \App\Filter\Recipe::addModel($model);
            }
        });
    }
}
