<?php

namespace App\Console\Commands\Build\Filters;

use Illuminate\Console\Command;

class Company extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:filter:companies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build company filter cache';

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
        \App\Company::whereNull('deleted_at')->chunk(100,function($models){
            foreach($models as $model){
//                new \App\Cached\Filter\Company($model);
                \App\Filter\Company::addModel($model);
            }
        });
    }
}
