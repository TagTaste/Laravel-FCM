<?php

namespace App\Console\Commands\Build\Filters;

use Illuminate\Console\Command;

class Job extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:filter:jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build job filter cache';

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
        \DB::table("job_filters")->delete();
    
        \App\Job::whereNull('deleted_at')->where('state',1)->chunk(100,function($models){
            foreach($models as $model){
               // new \App\Cached\Filter\Profile($model);
                \App\Filter\Job::addModel($model);
            }
        });
    }
}
