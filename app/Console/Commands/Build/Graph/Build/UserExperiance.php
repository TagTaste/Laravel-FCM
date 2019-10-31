<?php

namespace App\Console\Commands\Build\Graph\Build;

use Illuminate\Console\Command;
use Vinelab\NeoEloquent\Exceptions\NeoEloquentException;

class UserExperiance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:graph:userExperiance';

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
        \App\Profile\Experience::chunk(200, function($models) use($counter) {
            foreach($models as $model) {
                echo "\n".$counter." | id: ".(int)$model['id']."| profile_id: ".(int)$model['profile_id']."\n";
                $model->addUserExperience();
                $counter = $counter + 1;
            }
        });
    }
}
