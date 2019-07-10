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
            $cuisine_exist = \App\Neo4j\Cuisines::where('cuisineId', (int)$cuisine_type->id)->where('name',$cuisine_type->name)->first();
            if (!$cuisine_exist) {
                echo "Cuisine type id: ".(int)$cuisine_type->id." and name:".$cuisine_type->name." not exist. \n";
                \App\Neo4j\Cuisines::create([
                    "cuisineId" => (int)$cuisine_type->id,
                    "name" => $cuisine_type->name
                ]);
            } else {
                echo "Cuisine type id: ".(int)$cuisine_type->id." and name:".$cuisine_type->name." already exist. \n";
            }
        }
        $counter = 1;
        \App\Recipe\Profile::whereNull('deleted_at')->chunk(200, function($profiles) use($counter) {
            $counter = 1;
            foreach($profiles as $model) {
                $profileId = (int)$model->user_id;
                $cuisineIds =  \DB::table('profiles_cuisines')->where('profile_id',$profileId)->get()->pluck('cuisine_id');
                if (count($cuisineIds)) {
                    $user = \App\Neo4j\User::where('profileId', $profileId)->first();
                    foreach ($cuisineIds as $key => $cuisineId) {
                        $cuisine_type = \App\Neo4j\Cuisines::where('cuisineId', $cuisineId)->first();
                        if (!$cuisine_type) {
                            echo $counter." | Cuisine Type id: ".$cuisineId." not exist , Profile id: ".$profileId." exist. \n";
                        } else {
                            $cuisineTypeHaveUser = $cuisine_type->have->where('profileId', $profileId)->first();
                            if (!$cuisineTypeHaveUser) {
                                $relation = $cuisine_type->have()->attach($user);
                                if ($relation) {
                                    echo $counter." | Cuisine Type id: ".$cuisineId.", Profile id: ".$profileId." association success. \n";
                                } else {
                                    echo $counter." | Cuisine Type id: ".$cuisineId.", Profile id: ".$profileId." association failed. \n";
                                }
                            } else {
                                echo $counter." | Cuisine Type id: ".$cuisineId.", Profile id: ".$profileId." already associated. \n";
                            }
                        }
                    }
                } else {
                    echo $counter." | Profile id: ".$profileId.", have no cousines associated. \n";
                }
                $counter = $counter + 1;
                echo "\n ===================== \n";
            }
        });       
    }
}
