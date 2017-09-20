<?php

namespace App\Console\Commands\Build\Search;

use Illuminate\Console\Command;

class Job extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:search:job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild search job';

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
        \App\Job::chunk(100,function($models){
            foreach($models as $model){
                \App\Documents\Job::create($model);
            }
        });
    }
}
