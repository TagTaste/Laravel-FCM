<?php

namespace App\Jobs;

use App\Channel\Payload;
use App\Collaborate;
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
        file_put_contents(storage_path("logs") . "/nikhil_delete.txt", $this->profile_id, FILE_APPEND); 
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
            
    }

    function update_elastic_search(){
        $profile = \App\Profile::where('id', $this->profile_id)->whereNull('deleted_at')->get();
        if($this->deactivate && !empty($profile)){
            //deactivate user in elastic search
            \App\Documents\Profile::delete($profile);
        }else if(!empty($profile)){
            //activate user in elastic search
            \App\Documents\Profile::create($model);
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
        ->pluck('id')->toArray();

        Payload::where('model','App\Surveys')->whereIn('model_id',$surveys)
        ->where('account_deactivated',!$this->deactivate)
        ->update(['account_deactivated'=>$this->deactivate]);

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

        //sharable shoutouts
        $shared_collabs = \DB::table('shoutout_shares')->where('profile_id',$this->profile_id)
        ->whereNull('deleted_at')->pluck('id')->toArray();     
        Payload::where('model','App\Shareable\Shoutout')->whereIn('model_id',$shared_collabs)
        ->where('account_deactivated',!$this->deactivate)
        ->update(['account_deactivated'=>$this->deactivate]);       
        
    }
}
