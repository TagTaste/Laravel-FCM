<?php

namespace App\Console\Commands\Build\Graph;

use App\Subscriber;
use Illuminate\Console\Command;
use GraphAware\Neo4j\Client\ClientBuilder;

class Follower extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:graph:follow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'rebuild following cache.';

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
        $client = ClientBuilder::create()->addConnection('default', 'http://neo4j:password@localhost:11003')->build();
        // $result = $client->run('MATCH (u:User {profileId: 2043})-[c:CAN_FOLLOW]-(f:User) WHERE c.sameHabbit = true RETURN u,c,f');
        // $result = $client->run('MATCH (u:User {profileId: 2043})-[c:FOLLOWS]-(f:User) WHERE c.status = 1 RETURN id(f) as ids');
        $data = array();
        $result = $client->run('
            MATCH (:FoodieType {foodieTypeId: 9})-[:HAVE]-(users:User), (user:User {profileId:8})
            WHERE users.profileId <> 8 AND not ((user)-[:FOLLOWS {status:1}]->(users))
            RETURN users LIMIT 10'
        );
        foreach ($result->getRecords() as $record) {
            array_push($data, $record->get('users')->values());
        }
        dd($data);

        $user1 = \App\Neo4j\User::where('profileId', 2043)->first();
        $user3 = \App\Neo4j\User::where('profileId', 2042)->first();
        $user2 = \App\Neo4j\User::where('profileId', 2044)->first();
        
        // $canFollow = $user1->follows()->detach($user2);
        $canFollow = $user1->follows()->attach($user2);
        $canFollow->status = 0;
        $canFollow->statusValue = "not_follow";
        $canFollow->save();
        
        // $canFollow = $user1->follows()->attach($user3);
        // $canFollow->status = 0;
        // $canFollow->statusValue = "not_follow";
        // $canFollow->save();
        
        // $canFollow = $user1->canFollow()->attach($user2);
        // $canFollow->sameHabbit = true;
        // $canFollow->sameCollboartion = true;
        // $canFollow->save();

        // $canFollow = $user1->follows()->edge($user2);
        // $canFollow->status = 1;
        // $canFollow->statusValue = "follow";
        // $canFollow->save();
        
        // $canFollow = $user1->canFollow()->edges()->toArray();
        // foreach ($canFollow as $key => $value) {
        //     var_dump($value->toArray());
        // }
        dd($canFollow);
        // $location = $relation->parent();
        // $user = $relation->related();
        // $relations = $user1->canFollowSamePollAnswer()->edges();
        // dd($relations);
        // $canFollowSamePollAnswer = $user1->canFollowSamePollAnswer()->attach($user2);
        // $canFollowSameHabbit = $user1->canFollowSameHabbit()->attach($user2);
        // $canFollowSameCollboartion = $user1->canFollowSameCollboartion()->attach($user2);
        // $canFollowSamePollAnswer = $user1->canFollowSamePollAnswer()->detach($user2);
        // $canFollowSameHabbit = $user1->canFollowSameHabbit()->detach($user2);
        // $canFollowSameCollboartion = $user1->canFollowSameCollboartion()->detach($user2);
        dd($user1, $user2, $canFollowSamePollAnswer, $canFollowSameHabbit, $canFollowSameCollboartion);
        
        // $user = \App\Neo4j\User::where('profileId', 984)->first();
        // $followedBy = \App\Neo4j\User::where('profileId', 2039)->first();
        // $user->canFollowSamePollAnswer()->attach($followedBy);
        // $followedBy->canFollowSamePollAnswer()->attach($user)
        // $relation = $user->canFollowSamePollAnswer()->detach($followedBy);
        // $followedBy->followers()->attach($user);
    }
}
