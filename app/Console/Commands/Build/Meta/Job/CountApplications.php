<?php

namespace App\Console\Commands\Build\Meta\Job;

use App\Application;
use App\Collaboration\Collaborator;
use Illuminate\Console\Command;

class CountApplications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:meta:job:countApplication';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild Job application Count';

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
        Application::chunk(200,function($models){
            foreach($models as $model){
                \Redis::hIncrBy("meta:job:" . $model->id,"applicationCount",1);
            }
        });
    }
}
