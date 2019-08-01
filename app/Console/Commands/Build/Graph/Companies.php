<?php

namespace App\Console\Commands\Build\Graph;

use Illuminate\Console\Command;
use Vinelab\NeoEloquent\Exceptions\NeoEloquentException;

class Companies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:graph:companies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuilds profile cache';

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
        $counter = 1;
        \App\Company::chunk(200, function($models) use($counter) {
            foreach($models as $model) {
                echo $counter." | id: ".(int)$model['id']." | name: ".$model['name']." | user_id: ".(int)$model['user_id']."\n";
                $model->addToGraph();
                $counter = $counter + 1;
            }
        });
    }
}
