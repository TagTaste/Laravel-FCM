<?php

namespace App\Console\Commands\Build\Graph;

use Illuminate\Console\Command;
use Vinelab\NeoEloquent\Exceptions\NeoEloquentException;

class Profiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:graph:profiles';

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
        \App\Recipe\Profile::whereNull('deleted_at')->chunk(200, function($profiles) use($counter) {
            foreach($profiles as $model) {
                $keyRequired = ['id','user_id','name', 'designation','handle','imageUrl','image_meta','isFollowing'];
                $data = array_intersect_key($model->toArray(), array_flip($keyRequired));
                
                $user = \App\Neo4j\User::where('profileId', (int)$data['user_id'])->first();
                if (!$user) {
                    echo $counter.' | userId: '.(int)$data['id'].', profileId: '.(int)$data['user_id'].', handle: '.$data['handle'].', name: '.$data['name'];
                    $userData = array(
                        'userId' => (int)$data['id'],
                        'profileId' => (int)$data['user_id'],
                        'handle' => $data['handle'],
                        'imageMeta' => $data['image_meta'],
                        'name' => $data['name'],
                        'designation' => $data['designation'],
                        'imageUrl' => $data['imageUrl']
                    );

                    $user = \App\Neo4j\User::create($userData);
                    echo $counter.' | UserId: '.(int)$data['id']. " not exist. \n";
                } else {
                    echo $counter.' | UserId: '.(int)$data['id']. " already exist. \n";
                }
                $counter = $counter + 1;
            }
        });
    }
}
