<?php

namespace App\Console\Commands;

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
        $inputs = [];
        $collaborators = \App\Collaboration\Collaborator::all();
        foreach ($collaborators as $collaborator) {
            $checkExist = \App\Collaborate\Applicant::where('collaborate_id',$collaborator->collaborate_id)->where('profile_id',$collaborator->profile_id)->exists();
            if(!$checkExist)
            {
                $applicant = new \App\Collaborate\Applicant;
                $applicant->collaborate_id = $collaborator->collaborate_id;
                $applicant->profile_id = $collaborator->profile_id;
                $applicant->company_id = $collaborator->company_id;
                $applicant->message = $collaborator->message;
                $applicant->is_invited = 0;
                $applicant->shortlisted_at = $collaborator->approved_on;
                $applicant->rejected_at = $collaborator->archived_at;
                $applicant->created_at = $collaborator->applied_on;
                $applicant->applier_address = null;
                $applicant->hut = 0;
                $applicant->save();
                
            }
        }
        $this->info('Tables Merged');
    }
}
