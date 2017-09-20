<?php

namespace App\Console\Commands\Build\Cache;

use Illuminate\Console\Command;

class Photo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:cache:photos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'rebuild photo cache';

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
        \App\Photo::chunk(200,function($models){
            foreach($models as $model){
                $model->addToCache();
            }
        });
    }
}
