<?php

namespace App\Console\Commands\Build\Graph\Build;

use Illuminate\Console\Command;
use Vinelab\NeoEloquent\Exceptions\NeoEloquentException;

class UserSurveys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:graph:userSurveys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuilds nodes and edges between profile and surveys';

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
        \App\Surveys::select('surveys.*')
        ->join('survey_answers', 'survey_answers.survey_id', '=', 'surveys.id')
        ->where('surveys.state',2)
        ->where('surveys.is_active',1)
        ->distinct('surveys')
        ->whereNull('surveys.deleted_at')
        ->whereNull('survey_answers.deleted_at')
        ->chunk(200, function($surveys) use($counter) {
            foreach($surveys as $model) {
                echo $counter." | survey id: ".$model['id']."\n";
                $model->addToGraph();
                
                $profileIds = \App\SurveyAnswers::where('survey_id',$model['id'])
                ->whereNull('deleted_at')
                ->distinct('profile_id')
                ->pluck('profile_id')->toArray();
                            
                foreach($profileIds as $pId){
                    $model->addParticipationEdge($pId);
                }
                $counter = $counter + 1;
            }
        });
    }
}
