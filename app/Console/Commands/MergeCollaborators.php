<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class MergeCollaborators extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'merge:collaborators';

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
        //
//        $inputs = [];
//        $collaborators = \App\Collaboration\Collaborator::all();
//        foreach ($collaborators as $collaborator) {
//            $checkExist = \App\Collaborate\Applicant::where('collaborate_id',$collaborator->collaborate_id)->where('profile_id',$collaborator->profile_id)->exists();
//            if(!$checkExist)
//            {
//                $applicant = new \App\Collaborate\Applicant;
//                $applicant->collaborate_id = $collaborator->collaborate_id;
//                $applicant->profile_id = $collaborator->profile_id;
//                $applicant->company_id = $collaborator->company_id;
//                $applicant->message = $collaborator->message;
//                $applicant->is_invited = 0;
//                $applicant->shortlisted_at = $collaborator->approved_on;
//                $applicant->rejected_at = $collaborator->archived_at;
//                if(isset($collaborator->archived_at) && !is_null())
//                {
//                    $now = Carbon::now()->toDateTimeString();
//                    $applicant->shortlisted_at = $now;
//                }
//                $applicant->created_at = $collaborator->applied_on;
//                $applicant->applier_address = null;
//                $applicant->hut = 0;
//                $applicant->save();
//
//            }
//        }
        \App\Collaborate\Applicant::with([])->where('id','>=', 20)
            ->orderBy('id')->chunk(100, function ($models) {
                foreach ($models as $model) {

                    if(isset($model->rejected_at) && !is_null($model->rejected_at))
                    {
                        continue;
                    }
                    $now = Carbon::now()->toDateTimeString();
                    $model->update(['shortlisted_at'=>$now]);
                }
            });
        $this->info('Tables Merged');
    }
}
