<?php

namespace App\Console\Commands\Build\Graph;

use Illuminate\Console\Command;
use Vinelab\NeoEloquent\Exceptions\NeoEloquentException;

class PublicReviewProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:graph:publicProducts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuilds public products cache';
    
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
        \App\PublicReviewProduct::whereNull('deleted_at')->chunk(200, function($products) use($counter) {
            foreach($products as $model) {
                echo $counter." | id: ".$model['id']."\n";
                $model->addToGraph();
                $counter = $counter + 1;
            }
        });
    }
}
