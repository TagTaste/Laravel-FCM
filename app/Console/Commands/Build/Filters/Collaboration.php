<?php

namespace App\Console\Commands\Build\Filters;

use Illuminate\Console\Command;

class Collaboration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:filter:collaborations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build collab filter cache';

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
        \DB::table("collaborate_filters")->delete();
        
        \App\Collaborate::whereNull('deleted_at')->where('state',1)->chunk(100,function($models){
            foreach($models as $model){
               // new \App\Cached\Filter\Profile($model);
                \App\Filter\Collaborate::addModel($model);
            }
        });
    }
}
