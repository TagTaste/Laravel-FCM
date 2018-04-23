<?php

namespace App\Console\Commands;

use App\Recipe\Profile;
use App\Subscriber;
use Carbon\Carbon;
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

        \DB::table('profiles')->orderBy('id')->chunk(100, function ($models) {
            foreach ($models as $model) {
                echo "profile id ".$model->id ." deleted at ".$model->deleted_at. "\n\n";
                if($model->deleted_at)
                {
                    $profileIds = \DB::table('subscribers')->where('channel_name','public.'.$model->id)->get();

                    foreach ($profileIds as $profileId)
                    {
                        echo "remove profile id $model->id from profile ".$profileId;

                        \Redis::sRem("following:profile:" . $profileId, $model->id);

                        //profiles that are following $channelOwner
                        \Redis::sRem("followers:profile:" . $model->id, $profileId);
                    }
                }
            }
        });

    }
}
