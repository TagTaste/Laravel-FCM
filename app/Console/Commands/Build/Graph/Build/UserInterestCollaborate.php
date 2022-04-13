<?php

namespace App\Console\Commands\Build\Graph\Build;

use Illuminate\Console\Command;
use Vinelab\NeoEloquent\Exceptions\NeoEloquentException;

class UserInterestCollaborate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:graph:userInterestCollaborate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuilds nodes and edges between profile and collaboration';

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
        \App\Collaborate::select('collaborates.*')
        ->join('collaborate_applicants', 'collaborate_applicants.collaborate_id', '=', 'collaborates.id')
        ->where('collaborates.state',1)
        ->distinct('collaborates')
        ->whereNull('collaborates.deleted_at')
        ->whereNull('collaborate_applicants.rejected_at')
        ->chunk(200, function($collaborates) use($counter) {
            foreach($collaborates as $model) {
                echo $counter." | Collab id: ".$model['id']."\n";
                $model->addToGraph();
                
                $profileIds = \App\Collaborate\Applicant::where('collaborate_id',$model['id'])
                ->whereNull('rejected_at')
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
