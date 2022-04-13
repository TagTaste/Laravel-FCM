<?php

namespace App\Console\Commands\Build\Cache;

use App\Surveys;
use Illuminate\Console\Command;

class Survey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:cache:surveys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'rebuild survey cache';

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
        Surveys::chunk(200,function($models) {
           foreach ($models as $model) {
                echo "Caching: survey:" .$model->id."\n";
                $model->addToCache();
           }
        });
    }
}