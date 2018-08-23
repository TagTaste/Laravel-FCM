<?php

namespace App\Console\Commands;

use App\Recipe\Profile;
use Illuminate\Console\Command;

class CollaborateApplicantFieldFilled extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collaborators:applicants';

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
        \DB::table("collaborate_applicants")->orderBy('id')->chunk(100,function($models){
            foreach($models as $model){
                $address = json_decode($model->applier_address,true);
                $city = isset($address['city']) ? $address['city'] : null;
                $profile = Profile::where('id',$model->profile_id)->first();
                \Log::info($profile->ageRange);
                \Log::info($profile->gender);
                \DB::table('collaborate_applicants')->where('id',$model->id)->update(['city'=>$city,'age_group'=>$profile->ageRange,'gender'=>$profile->gender]);
            }
        });
    }
}
