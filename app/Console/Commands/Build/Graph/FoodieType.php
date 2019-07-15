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
        dd("test");
        // $counter = 1;
        // \App\Recipe\Profile::whereNull('deleted_at')->chunk(200, function($profiles) use($counter) {
        //     $counter = 1;
        //     foreach($profiles as $model) {
        //         $food_type_id = (int)$model->foodie_type_id;
        //         $profileId = (int)$model->user_id;
        //         $food_type = \App\Neo4j\FoodieType::where('foodieTypeId', $food_type_id)->first();
        //         if (!$food_type) {
        //             echo $counter." | Foodie Type id: ".$food_type_id." not exist. \n";
        //         } else {
        //             $foodtypeHaveUser = $food_type->have->where('profileId', $profileId)->first();
        //             if (!$foodtypeHaveUser) {
        //                 $user = \App\Neo4j\User::where('profileId', $profileId)->first();
        //                 if (!$user) {
        //                     echo $counter." | Food Type id: ".$food_type_id." exist , Profile id: ".$profileId." not exist. \n";
        //                 } else {
        //                     $relation = $food_type->have()->attach($user);
        //                     $relation->status = 1;
        //                     $relation->statusValue = "have";
        //                     $relation->save();
        //                     echo $counter." | Food Type id: ".$food_type_id.", Profile id: ".$profileId." associated. \n";
        //                 }
        //             } else {
        //                 $user = \App\Neo4j\User::where('profileId', $profileId)->first();
        //                 $relation = $food_type->have()->edge($user);
        //                 $relation->status = 1;
        //                 $relation->statusValue = "have";
        //                 $relation->save();
        //                 echo $counter." | Food Type id: ".$food_type_id.", Profile id: ".$profileId." already associated. \n";
        //             }
        //         }
        //         $counter = $counter + 1;
        //     }
        // });
    }
}
