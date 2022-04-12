<?php

namespace App\Console\Commands\Build\Graph\Build;

use Illuminate\Console\Command;
use Vinelab\NeoEloquent\Exceptions\NeoEloquentException;

class UserPolling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:graph:userPolling';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuilds edges between profile and poll';

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
        \App\Polling::select('poll_questions.*')
        ->join('poll_votes', 'poll_votes.poll_id', '=', 'poll_questions.id')
        ->where('poll_questions.is_expired',0)
        ->distinct('poll_questions')
        ->whereNull('poll_questions.deleted_at')
        ->whereNull('poll_votes.deleted_at')
        ->chunk(200, function($polls) use($counter) {
            foreach($polls as $model) {
                echo $counter." | poll id: ".$model['id']."\n";
                $model->addToGraph();
                
                $profileIds = \App\PollingVote::where('poll_id',$model['id'])
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
