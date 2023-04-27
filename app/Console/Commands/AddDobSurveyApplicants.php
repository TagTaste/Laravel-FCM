<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helper;

class AddDobSurveyApplicants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:addDobSurveyApplicant';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'to sync dob from profiles to survey_applicants';

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
        $profiles = \DB::table("profiles")->whereNull("deleted_at")->get();
        
        foreach ($profiles as $profile) {
            $id = $profile->id;
            $dob = $profile->dob;
            if(isset($dob)){
                $generation = Helper::getGeneration(date("Y", strtotime($dob)));
                \DB::table("survey_applicants")->where('profile_id', $id)->update(["dob" => $dob, "generation"=>$generation]);    
            }
        }  
    }         
}
