<?php

namespace App\Console\Commands;

use App\Collaborate;
use App\Job;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ExpireReopen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ExpireReopen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'reopen expires jobs and collaboration';

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
        //this run only once after that remove from kernel.php this file
        \DB::table("jobs")->where('state',Job::$state[2])->orderBy('id')->chunk(100,function($models){
            foreach($models as $model){
                \DB::table('jobs')->where('id',$model->id)->update(['state'=>Job::$state[0],'deleted_at'=>null,'expires_on'=>Carbon::now()->addMonth()->toDateTimeString()]);
            }
        });

        \DB::table("collaborates")->where('state',Collaborate::$state[2])->orderBy('id')->chunk(100,function($models){
            foreach($models as $model){
                \DB::table('collaborates')->where('id',$model->id)->update(['state'=>Collaborate::$state[0],'deleted_at'=>null,'expires_on'=>Carbon::now()->addMonth()->toDateTimeString()]);
            }
        });

    }
}
