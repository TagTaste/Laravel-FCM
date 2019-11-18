<?php

namespace App\Console\Commands\Build\Graph;

use Illuminate\Console\Command;
use Vinelab\NeoEloquent\Exceptions\NeoEloquentException;

class Specializations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:graph:specializations';

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
        $specialization_types = \DB::table('specializations')->get();
        foreach ($specialization_types as $key => $specialization_type) {
            $specialization_exist = \App\Neo4j\Specialization::
                where('specialization_id', (int)$specialization_type->id)->
                where('name',$specialization_type->name)->first();
            if (!$specialization_exist) {
                echo "Specialization Type id: ".(int)$specialization_type->id." and name:".$specialization_type->name." not exist. \n";
                \App\Neo4j\Specialization::create([
                    "specialization_id" => (int)$specialization_type->id,
                    "name" => $specialization_type->name
                ]);
            } else {
                echo "Specialization Type id: ".(int)$specialization_type->id." and name:".$specialization_type->name." alredy exist. \n";
            }
        }
    }
}
