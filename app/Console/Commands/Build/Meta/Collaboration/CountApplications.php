<?php

namespace App\Console\Commands\Build\Meta\Collaboration;

use App\Collaboration\Collaborator;
use Illuminate\Console\Command;

class CountApplications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:meta:collaborate:countApplication';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild collaboration application Count';

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
        Collaborator::chunk(200,function($models){
            foreach($models as $model){
                \Redis::hset("meta:collaborate:" . $model->collaborate_id,"applicationCount",0);
            }
        });
        Collaborator::chunk(200,function($models){
            foreach($models as $model){
                \Redis::hIncrBy("meta:collaborate:" . $model->collaborate_id,"applicationCount",1);
            }
        });
    }
}
