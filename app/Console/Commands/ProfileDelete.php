<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

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
////        if(!$this->confirm("Delete profile $profileId?")){
////            $this->info("NOT deleting.");
////            return;
////        }
////
////        $this->info("deleting profile $profileId");
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
//        $user = \DB::table("users")->select("users.*")
//            ->join('profiles','profiles.user_id','=','users.id')
//            ->where('profiles.id','like',$this->profileId)->first();
//        if(!$user){
//            echo "Could not find user";
//        }
//        $profile = \DB::table("profiles")->where('user_id',$user->id)->first();
//        if(!$profile){
//            echo "Could not find profile.";
//        }
//
//        //if($this->confirm("Delete " . $profile->id . " " . $user->name . "?")){
//        \App\Filter\Profile::removeModel($profile->id);
//        $profileModel = \App\Profile::where('user_id',$user->id)->first();
//        \App\Documents\Profile::delete($profileModel);
//        $profileModel->removeFromCache();
//
//        \DB::table("social_accounts")->where('user_id',$profile->user_id)->delete();
//
//        $now = \Carbon\Carbon::now();
//        \DB::table("profiles")->where('id',$profile->id)->update(['updated_at'=>$now->toDateTimeString()
//            ,'deleted_at'=>$now->toDateTimeString()]);
//
//        \DB::table("users")->where('email','like',$this->profileId)
//            ->update(['email'=>$user->email . str_random(4),'deleted_at'=>$now->toDateTimeString()]);


        \DB::table('profiles')->orderBy('id')->chunk(100, function ($models) {
            foreach ($models as $model) {
                echo "profile id ".$model->id ." deleted at ".$model->deleted_at. "\n\n";
                if($model->deleted_at)
                {
                    $profileIds = \DB::table('subscribers')->where('channel_name','public.'.$model->id)->get();

                    foreach ($profileIds as $profileId)
                    {
                        echo "remove profile id $model->id from profile ".$profileId->profile_id ."\n\n";

                        Redis::sRem("followers:profile:" . $profileId->profile_id, $model->id);

                        //profiles that are following $channelOwner
                        Redis::sRem("followers:profile:" . $model->id, $profileId->profile_id);

                        Redis::sRem("following:profile:" . $profileId->profile_id, $model->id);

                        //profiles that are following $channelOwner
                        Redis::sRem("following:profile:" . $model->id, $profileId->profile_id);
                    }
                }
            }
        });

        \DB::table('profiles')->orderBy('id')->chunk(100, function ($models) {
            foreach ($models as $model) {
                echo "profile id ".$model->id ." deleted at ".$model->deleted_at. "\n\n";
                if($model->deleted_at)
                {

                    $subscribers = \DB::table('subscribers')->where('profile_id',$model->id)
                        ->where('channel_name','like','company.public.%')->get();

                    foreach ($subscribers as $subscriber)
                    {
                        $channel = $subscriber->channel_name;
                        $channel = explode('.',$channel);
                        echo "comapny id ".$channel[2] ." deleted profile id ".$model->id. "\n\n";
                        Redis::sRem("following:profile:" . $model->id, "company.$channel[2]");
                        Redis::sRem("followers:company:" . $channel[2], $model->id);
                    }

                }
            }
        });
        //}
    }
}
