<?php

namespace App\Jobs;

use App\Channel\Payload;
use App\Collaborate;
use App\Photo;
use App\Polling;
use App\Recipe\Profile;
use App\Shoutout;
use App\Surveys;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class AccountDeactivateChanges implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $profile_id;
    public $deactivate;

    public function __construct($profile_id, $deactivate)
    {
        $this->profile_id = $profile_id;
        $this->deactivate = $deactivate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //deactivate feed
        $this->update_user_feed();
        $this->update_activity();
        $this->update_elastic_search();
        $this->remove_fcm_token();
        file_put_contents(storage_path("logs") . "/nikhil_delete.txt", $this->profile_id, FILE_APPEND); 
    }
    
    function remove_fcm_token(){
        if($this->deactivate){
            DB::table('app_info')->where('profile_id',$this->profile_id)->delete();
        }
    }
    
    function update_activity(){
        //survey
        $collab_check_state = 1;
        $collab_final_state = 5;
        
        $survey_check_state = 2;
        $survey_final_state = 3;

        if(!$this->deactivate){
            $collab_check_state = 5;
            $collab_final_state = 1;
            
            $survey_check_state = 3;
            $survey_final_state = 2;
        }
        
        $surveys = Surveys::where('profile_id',$this->profile_id)
        ->where('state',$survey_check_state)
        ->where('account_deactivated',!$this->deactivate)
        ->whereNull('company_id')
        ->whereNull('deleted_at')->update(['state'=>$survey_final_state, 'account_deactivated'=>$this->deactivate]);
        
        
        //collaboration
        $collabs = Collaborate::where('profile_id',$this->profile_id)
        ->where('state','=',$collab_check_state)
        ->where('account_deactivated',!$this->deactivate)
        ->whereNull('company_id')->update(['state'=>$collab_final_state, 'account_deactivated'=>$this->deactivate]);
        
        //Polling
        $polls = Polling::where('profile_id',$this->profile_id)
        ->where('account_deactivated',!$this->deactivate)
        ->whereNull('company_id')->whereNull('deleted_at')->update(['account_deactivated'=>$this->deactivate]);
        
        
        //Shoutout
        $shououts = Shoutout::where('profile_id',$this->profile_id)
        ->where('account_deactivated',!$this->deactivate)
        ->whereNull('company_id')->whereNull('deleted_at')->update(['account_deactivated'=>$this->deactivate]);

        //Photos
        $photo_id_list = DB::table('profile_photos')->where('profile_id',$this->profile_id)->pluck('photo_id')->toArray();
        $photos = Photo::whereIn('id',$photo_id_list)->where('account_deactivated',!$this->deactivate)->whereNull('deleted_at')->update(['account_deactivated'=>$this->deactivate]);
        
    }

    function update_elastic_search(){
        $profile = \App\Profile::where('id', $this->profile_id)->whereNull('deleted_at')->first();
        if($this->deactivate && !empty($profile)){
            //deactivate user in elastic search
            \App\Documents\Profile::delete($profile);
        }else if(!empty($profile)){
            //activate user in elastic search
            \App\Documents\Profile::create($profile);
        }
    }
    
    function update_user_feed(){
        //polls
        $polls = Polling::where('profile_id',$this->profile_id)
        ->whereNull('company_id')
        ->whereNull('deleted_at')
        ->pluck('id')->toArray();
        
        Payload::where('model','App\Polling')->whereIn('model_id',$polls)
        ->where('account_deactivated',!$this->deactivate)
        ->update(['account_deactivated'=>$this->deactivate]);
        
        //update the landing page
        if($this->deactivate){
            DB::table('landing_banner')->whereNull('deleted_at')->where('model_name','polling')->whereIn('model_id',$polls)->update(['deleted_at'=>Carbon::now()]);
        }
        
        //update the neo4j
        $poll_list = Polling::where('profile_id',$this->profile_id)
        ->whereNull('company_id')
        ->whereNull('deleted_at')->get();
        foreach($poll_list as $poll){
            if($this->deactivate){
                $poll->removeFromGraph();
            }else{
                $poll->addToGraph();
            }
        }
        
        //shared polls
        $shared_polls = \DB::table('polling_shares')->where('profile_id',$this->profile_id)
        ->whereNull('deleted_at')->pluck('id')->toArray();        
        
        Payload::where('model','App\Shareable\Polling')->whereIn('model_id',$shared_polls)
        ->where('account_deactivated',!$this->deactivate)
        ->update(['account_deactivated'=>$this->deactivate]);
        
        
        //surveys
        $surveys = Surveys::where('profile_id',$this->profile_id)
        ->whereNull('company_id')
        ->whereNull('deleted_at')
        ->pluck('payload_id')->toArray();
        
        Payload::where('model','App\Surveys')->whereIn('id',$surveys)
        ->where('account_deactivated',!$this->deactivate)
        ->update(['account_deactivated'=>$this->deactivate]);

         //update the landing page
         if($this->deactivate){
            DB::table('landing_banner')->whereNull('deleted_at')->where('model_name','surveys')->whereIn('model_id',$surveys)->update(['deleted_at'=>Carbon::now()]);
         }

        //update neo4j
        $survey_list = Surveys::where('profile_id',$this->profile_id)
        ->whereNull('company_id')
        ->whereNull('deleted_at')->get();
        foreach($survey_list as $survey){
            if($this->deactivate){
                $survey->removeFromGraph();
            }else{
                $survey->addToGraph();
            }
        }

        //shared surveys
        $shared_surveys = \DB::table('surveys_shares')->where('profile_id',$this->profile_id)
        ->whereNull('deleted_at')->pluck('id')->toArray(); 
        Payload::where('model','App\Shareable\Surveys')->whereIn('model_id',$shared_surveys)
        ->where('account_deactivated',!$this->deactivate)
        ->update(['account_deactivated'=>$this->deactivate]);

        //collaborates
        $collabs = Collaborate::where('profile_id',$this->profile_id)
        ->where('state','!=',2)
        ->whereNull('company_id')
        ->pluck('id')->toArray();

        Payload::where('model','App\Collaborate')->whereIn('model_id',$collabs)
        ->where('account_deactivated',!$this->deactivate)
        ->update(['account_deactivated'=>$this->deactivate]);

        
        //update the landing page
        if($this->deactivate){
            DB::table('landing_banner')->whereNull('deleted_at')->whereIn('model_name',['collaborate','product-review'])->whereIn('model_id',$collabs)->update(['deleted_at'=>Carbon::now()]);
        }
        

        //update neo4j
        $collab_list = Collaborate::where('profile_id',$this->profile_id)
        ->where('state','!=',2)
        ->whereNull('company_id')->get();
        foreach($collab_list as $collab){
            if($this->deactivate){
                $collab->removeFromGraph();
            }else{
                $collab->addToGraph();
            }
        }
        
        //shared collaborations
        $shared_collabs = \DB::table('collaborate_shares')->where('profile_id',$this->profile_id)
        ->whereNull('deleted_at')->pluck('id')->toArray(); 
        Payload::where('model','App\Shareable\Collaborate')->whereIn('model_id',$shared_collabs)
        ->where('account_deactivated',!$this->deactivate)
        ->update(['account_deactivated'=>$this->deactivate]);

        //shoutouts
        $shoutouts = Shoutout::where('profile_id',$this->profile_id)
        ->whereNull('deleted_at')
        ->whereNull('company_id')
        ->pluck('id')->toArray();
        
        
        Payload::where('model','App\Shoutout')->whereIn('model_id',$shoutouts)
        ->where('account_deactivated',!$this->deactivate)
        ->update(['account_deactivated'=>$this->deactivate]);


         //update the landing page
         if($this->deactivate){
            DB::table('landing_banner')->whereNull('deleted_at')->where('model_name','shoutout')->whereIn('model_id',$shoutouts)->update(['deleted_at'=>Carbon::now()]);
         }
         
        
        //sharable shoutouts
        $shared_shoutouts = DB::table('shoutout_shares')->where('profile_id',$this->profile_id)
        ->whereNull('deleted_at')->pluck('id')->toArray();     
        Payload::where('model','App\Shareable\Shoutout')->whereIn('model_id',$shared_shoutouts)
        ->where('account_deactivated',!$this->deactivate)
        ->update(['account_deactivated'=>$this->deactivate]);    
        
        
        //Photos
        $photo_id_list = DB::table('profile_photos')->where('profile_id',$this->profile_id)->pluck('photo_id')->toArray();
        $photos = Photo::whereIn('id',$photo_id_list)->whereNull('deleted_at')->pluck('id')->toArray();

        Payload::whereIn('model',['App\Photos','App\V2\Photo'])->whereIn('id', $photos)->where('account_deactivated',!$this->deactivate)->update(['account_deactivated'=>$this->deactivate]);
        
        
         //sharable photos
         $shared_photos = DB::table('photo_shares')->where('profile_id',$this->profile_id)
         ->whereNull('deleted_at')->pluck('payload_id')->toArray();
         Payload::where('model','App\Shareable\Photo')->whereIn('id',$shared_photos)->where('account_deactivated',!$this->deactivate)->update(['account_deactivated'=>$this->deactivate]);  
         
         //shared products
         $shared_products = DB::table('public_review_product_shares')->where('profile_id',$this->profile_id)
         ->whereNull('deleted_at')->pluck('payload_id')->toArray();
         Payload::where('model','App\Shareable\Product')->whereIn('id',$shared_products)->where('account_deactivated',!$this->deactivate)->update(['account_deactivated'=>$this->deactivate]);  

    }
}
