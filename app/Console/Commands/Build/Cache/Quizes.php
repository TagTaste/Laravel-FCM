<?php

namespace App\Console\Commands\Build\Cache;

use App\Quiz;
use Illuminate\Console\Command;

class Quizes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:cache:quiz';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'rebuild quiz cache';

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
        Quiz::chunk(200,function($models) {
           foreach ($models as $model) {
                echo "Caching: quiz:" .$model->id."\n";
                $model->addToCache();
           }
        });
    }
}