<?php

namespace App\Console\Commands\Build\Graph;

use Illuminate\Console\Command;
use Vinelab\NeoEloquent\Exceptions\NeoEloquentException;

class FoodieType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:graph:foodieType';

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
        $foodie_types = \DB::table('foodie_type')->get();
        foreach ($foodie_types as $key => $foodie_type) {
            $foodie_type_exist = \App\Neo4j\FoodieType::where('foodieTypeId', (int)$foodie_type->id)
                ->where('name',$foodie_type->name)
                ->first();
            if (!$foodie_type_exist) {
                echo "Foodie Type id: ".(int)$foodie_type->id." and name:".$foodie_type->name." not exist. \n";
                \App\Neo4j\FoodieType::create([
                    "foodie_type_id" => (int)$foodie_type->id,
                    "name" => $foodie_type->name
                ]);
            } else {
                echo "Foodie Type id: ".(int)$foodie_type->id." and name:".$foodie_type->name." alredy exist. \n";
            }
        }
    }
}
