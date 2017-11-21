<?php

namespace App\Console\Commands\Build\Filters;

use Illuminate\Console\Command;

class Profile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:filter:profiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build profile filter cache';

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
        \DB::table("profile_filters")->delete();
        
        \App\Recipe\Profile::whereNull('deleted_at')->chunk(200,function($models){
            foreach($models as $model){
               // new \App\Cached\Filter\Profile($model);
                \App\Filter\Profile::addModel($model);
            }
        });
    }
}
