<?php

namespace App\Console\Commands;

use App\Recipe\Profile;
use App\Subscriber;
use Illuminate\Console\Command;

class ProfileDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profile:delete';
    
    private $profileId;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
//        $profileId = $this->argument('profileId');
//
//        if(!$this->confirm("Delete profile $profileId?")){
//            $this->info("NOT deleting.");
//            return;
//        }
//
//        $this->info("deleting profile $profileId");
//        $this->profileId = $profileId;
        $this->delete();
    }
    
    private function delete()
    {
        //add all delete methods here
        
        //delete followers : db + redis
        //delete following : db + redis
        
        //delete comments
        //delete shares - jobs, collaborates, photos, recipes, shoutouts
        //delete likes
        
        //delete chat messages
        //delete chats
        
        //delete collaborations
        //remove entry from collaborators - or show inactive profile
        
        //delete jobs
        //delete applications
        
        //delete photos
        
        //delete recipes
        
        //delete shoutouts
        
        
        //delete model
        $this->deleteModel();
    }
    
    private function deleteModel()
    {
//        $this->info("Deleting model " . $this->profileId);
//        $profile = \App\Profile::where('id',$this->profileId)->first();
//        if(!$profile){
//            echo "Could not find profile.";
//        }
//
//        if($this->confirm("Delete " . $profile->id . "?")){
//            \DB::table("social_accounts")->where('user_id',$profile->user_id)->delete();
//
//            if($profile->user){
//                $profile->user->delete();
//            }
//
//            $profile->delete();
//        }
        Profile::orderBy('id')->chunk(200,function($models){
            Subscriber::join("profiles",'profiles.id','=','subscribers.profile_id')
                ->whereNull('profiles.deleted_at')
                ->where('subscribers.profile_id',$models->id)
                ->where('subscribers.channel_name','like','public.%')
                ->whereNull('subscribers.deleted_at')->chunk(1000,function($subscribers){
                    echo "************* Count " . $subscribers->count() . "\n\n\n\n\n";
                    $count = 0;
                    foreach($subscribers as $model){

                        $channel = explode(".",$model->channel_name);
                        $channelOwnerProfileId = last($channel);
                        $profile_id = $channelOwnerProfileId;
                        echo "profile id which is deleted .".$channelOwnerProfileId . "\n\n";

                        $channelOwnerProfileId = "profile:small:".$channelOwnerProfileId;
                        $profile = \Redis::mget($channelOwnerProfileId);

                        if(is_null($profile))
                        {
                            echo "ye delete nhi h sahi se .".last($channel) . "\n\n";
                        }
                        $profile = Profile::where('id',$profile_id)->whereNull('deleted_at')->first();

                        if(!isset($profile))
                        {
                            $count ++;
                            $model->delete();
                            echo "ye delete nhi h sahi se .".$profile_id . "\n\n";
                        }

                    }
                    echo "total profile ".$count;
                });
        });
    }
}
