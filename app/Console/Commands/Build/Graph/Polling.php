<?php

namespace App\Console\Commands\Build\Graph;

use Illuminate\Console\Command;
use Vinelab\NeoEloquent\Exceptions\NeoEloquentException;

class Polling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:graph:polling';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuilds polls cache';
    
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
        \App\Polling::where('is_expired',0)->whereNull('deleted_at')->chunk(200, function($polls) use($counter) {
            foreach($polls as $model) {
                echo $counter." | id: ".$model['id']."\n";
                $model->addToGraph();
                $counter = $counter + 1;
            }
        });
    }
}
