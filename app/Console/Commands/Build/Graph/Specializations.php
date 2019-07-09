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
                where('specializationId', (int)$specialization_type->id)->
                where('name',$specialization_type->name)->first();
            if (!$specialization_exist) {
                echo "Specialization Type id: ".(int)$specialization_type->id." and name:".$specialization_type->name." not exist. \n";
                \App\Neo4j\Specialization::create([
                    "specializationId" => (int)$specialization_type->id,
                    "name" => $specialization_type->name
                ]);
            } else {
                echo "Specialization Type id: ".(int)$specialization_type->id." and name:".$specialization_type->name." alredy exist. \n";
            }
        }
        
        $counter = 1;
        \App\Recipe\Profile::whereNull('deleted_at')->chunk(200, function($profiles) use($counter) {
            $counter = 1;
            foreach($profiles as $model) {
                $profileId = (int)$model->user_id;
                $specializationIds =  \DB::table('profile_specializations')->where('profile_id',$profileId)->get()->pluck("specialization_id");
                if (count($specializationIds)) {
                    $user = \App\Neo4j\User::where('profileId', $profileId)->first();
                    foreach ($specializationIds as $key => $specializationId) {
                        $specialization_type = \App\Neo4j\Specialization::where('specializationId', $specializationId)->first();
                        if (!$specialization_type) {
                            echo $counter." | Specialization Type id: ".$specializationId." not exist , Profile id: ".$profileId." exist. \n";
                        } else {
                            $specializationTypeHaveUser = $specialization_type->have->where('profileId', $profileId)->first();
                            if (!$specializationTypeHaveUser) {
                                $relation = $specialization_type->have()->attach($user);
                                $relation->status = 1;
                                $relation->statusValue = "have";
                                $relation->save();
                                if ($relation) {
                                    echo $counter." | Specialization Type id: ".$specializationId.", Profile id: ".$profileId." association success. \n";
                                } else {
                                    echo $counter." | Specialization Type id: ".$specializationId.", Profile id: ".$profileId." association failed. \n";
                                }
                            } else {
                                $relation = $specialization_type->have()->edge($user);
                                $relation->status = 1;
                                $relation->statusValue = "have";
                                $relation->save();
                                echo $counter." | Specialization Type id: ".$specializationId.", Profile id: ".$profileId." already associated. \n";
                            }
                        }
                    }
                } else {
                    echo $counter." | Profile id: ".$profileId.", have no specializations associated. \n";
                }
                $counter = $counter + 1;
                echo "\n ===================== \n";
            }
        });
    }
}
