<?php

namespace App\Console\Commands\Build\Cache;

use App\Collaborate;
use Illuminate\Console\Command;

class Collaboration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:cache:collaborations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'rebuild collab cache';

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
        Collaborate::where('id',1844)->chunk(200,function($models) {
           foreach ($models as $model) {
                echo "Caching: collaborate:" .$model->id."\n";
                $model->addToCache();
                echo "Caching: collaborate:" .$model->id.":V2 \n\n";
                $model->addToCacheV2();
           }
        });
    }
}
