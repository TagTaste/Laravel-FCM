<?php

namespace App\Console\Commands\Build\Search;

use Illuminate\Console\Command;

class Profile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:search:profile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild search profile';

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
        \App\Profile::chunk(100,function($models){
            foreach($models as $model){
                \App\Documents\Profile::create($model);
            }
        });
    }
}
