<?php

namespace App\Console\Commands\Build\Graph;

use Illuminate\Console\Command;
use Vinelab\NeoEloquent\Exceptions\NeoEloquentException;

class Cuisines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:graph:cuisines';

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
        $cuisine_types = \DB::table('cuisines')->get();
        foreach ($cuisine_types as $key => $cuisine_type) {
            $cuisine_exist = \App\Neo4j\Cuisines::where('cuisine_id', (int)$cuisine_type->id)->where('name',$cuisine_type->name)->first();
            if (!$cuisine_exist) {
                echo "Cuisine type id: ".(int)$cuisine_type->id." and name:".$cuisine_type->name." not exist. \n";
                \App\Neo4j\Cuisines::create([
                    "cuisine_id" => (int)$cuisine_type->id,
                    "name" => $cuisine_type->name
                ]);
            } else {
                echo "Cuisine type id: ".(int)$cuisine_type->id." and name:".$cuisine_type->name." already exist. \n";
            }
        }
    }
}
