<?php

namespace App\Console\Commands\Build\Search;

use App\Documents\Survey;
use Illuminate\Console\Command;

class Surveys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:search:surveys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild search survey';

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
        \App\Surveys::chunk(100,function($models){
            foreach($models as $model){
                $this->info("Building " . $model->id);
                \App\Documents\Surveys::create($model);
            }
        });
    }
}
