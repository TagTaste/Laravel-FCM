<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ModelFlagReason;
use App\FlagReason;
use App\V2\Collaborate;
use App\Surveys;
use App\PublicReviewProduct;

class AddFlagReasons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:flag:reasons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add flag reasons for the reviews flagged by the system';

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
        //Map profile id and company ids
        ModelFlagReason::chunk(200, function($modelFlagReasons){
            $system_flag_reasons = FlagReason::pluck('slug','id');
            foreach($modelFlagReasons as $modelFlagReason){
                $modelClass = ($modelFlagReason->model == 'BatchAssign') ? 'App\\Collaborate\\'.$modelFlagReason->model : 'App\\'.$modelFlagReason->model;
                $modelData = $modelClass::find($modelFlagReason->model_id);
                $profile_id = null;
                $company_id = null;

                // find model id and get profile_id, company_id from the relevant tables
                switch ($modelFlagReason->model) {
                    case 'BatchAssign':
                        $collaborateData = Collaborate::select('profile_id', 'company_id')->find($modelData->collaborate_id);
                        if(isset($collaborateData) && !empty($collaborateData)){
                            $profile_id = $collaborateData->profile_id;
                            $company_id = $collaborateData->company_id;
                        }
                        break;
                    case 'SurveyAttemptMapping':
                        $surveyData = Surveys::select('profile_id', 'company_id')->find($modelData->survey_id);
                        if(isset($surveyData) && !empty($surveyData)){
                            $profile_id = $surveyData->profile_id;
                            $company_id = $surveyData->company_id;
                        }  
                        break;
                    case 'PublicReviewUserTiming':
                        $publicReviewData = PublicReviewProduct::select('company_id')->find($modelData->product_id);
                        if(isset($publicReviewData) && !empty($publicReviewData)){
                            $company_id = $publicReviewData->company_id;
                        } 
                        break;
                }

                $reason_slug = $system_flag_reasons[$modelFlagReason->flag_reason_id];
                $modelFlagReason->where('model_id', $modelFlagReason->model_id)->where('model', $modelFlagReason->model)->update(['reason' => config("constant.FLAG_REASONS_TEXT.".$reason_slug), 'slug' => config("constant.FLAG_SLUG.SYSTEM"), 'profile_id' => $profile_id, 'company_id' => $company_id]);
            }        
        });
    }
}
