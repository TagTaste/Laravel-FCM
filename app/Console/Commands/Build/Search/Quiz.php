<?php

namespace App\Console\Commands\Build\Search;

use Illuminate\Console\Command;

class Quiz extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:search:quiz';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild search quiz';

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
        \App\Quiz::chunk(100,function($models){
            foreach($models as $model){
                $this->info("Building " . $model->id);
                \App\Documents\Quiz::create($model);
            }
        });
    }
}
