<?php

namespace App\Console\Commands\Build\Cache;

use App\Collaborate;
use Illuminate\Console\Command;

class Batches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:cache:batches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'rebuild batch cache';

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
        Collaborate\Batches::chunk(200,function($models){
            foreach($models as $model){
                $model->addToCache();
            }
        });
    }
}
