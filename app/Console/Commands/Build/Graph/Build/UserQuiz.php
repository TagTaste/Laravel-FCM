<?php

namespace App\Console\Commands\Build\Graph\Build;

use Illuminate\Console\Command;
use Vinelab\NeoEloquent\Exceptions\NeoEloquentException;
use GraphAware\Neo4j\Client\ClientBuilder;


class UserQuiz extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:graph:userQuiz';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuilds nodes and edges between profile and quizes';

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
        \App\Quiz::select('quizes.*')
        ->join('quiz_answers', 'quiz_answers.quiz_id', '=', 'quizes.id')
        ->where('quizes.state',2)
        ->distinct('quizes')
        ->whereNull('quizes.deleted_at')
        ->whereNull('quiz_answers.deleted_at')
        ->chunk(200, function($quizes) use($counter) {
            foreach($quizes as $model) {
                echo $counter." | quiz id: ".$model['id']."\n";
                $model->addToGraph();
                
                $profileIds = \App\QuizAnswers::where('quiz_id',$model['id'])
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
