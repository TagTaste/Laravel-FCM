<?php

namespace App\Console\Commands\Build\Search;

use Illuminate\Console\Command;

class Recipe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:search:recipe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild search recipe';

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
                \App\Documents\Recipe::create($model);
            }
        });
    }
}
