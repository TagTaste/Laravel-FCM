<?php

namespace App\Console\Commands\Build\Search;

use App\Documents\Collaborate;
use Illuminate\Console\Command;

class Collaboration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:search:collaboration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild search collaboration';

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
        \App\Collaborate::chunk(100,function($models){
            foreach($models as $model){
                \App\Documents\Collaborate::create($model);
            }
        });
    }
}
