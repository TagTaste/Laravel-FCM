<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use App\QuizApplicants;

class QuizApplicationStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quiz:applicationstatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'to sync application status of quiz applicants';

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
        $quiz_applicants = QuizApplicants::whereNull("deleted_at")->get();
        foreach ($quiz_applicants as $applicant) {
            Redis::set("quiz:application_status:$applicant->quiz_id:profile:$applicant->profile_id", $applicant->application_status);
        }  
    }
}
