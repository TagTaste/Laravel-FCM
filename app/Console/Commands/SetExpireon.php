<?php

namespace App\Console\Commands;

use App\Collaborate;
use App\Events\DeleteFeedable;
use App\Job;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SetExpireon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SetExpireon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'set expires_on in jobs and collaboration';

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
        \DB::table("jobs")->where('expires_on','<=',Carbon::now()->toDateTimeString())->orderBy('id')->chunk(100,function($models){
            foreach($models as $model){
                event(new DeleteFeedable($model));
                \DB::table('jobs')->where('id',$model->id)->update(['state'=>Job::$state[2]]);
            }
        });

        \DB::table("jobs")->whereRaw('deleted_at < expires_on')->whereNotNull('deleted_at')->orderBy('id')->chunk(100,function($models){
            foreach($models as $model){
                \DB::table('jobs')->where('id',$model->id)->update(['state'=>Job::$state[1]]);
            }
        });

        \DB::table("collaborates")->where('expires_on','<=',Carbon::now()->toDateTimeString())->orderBy('id')->chunk(100,function($models){
            foreach($models as $model){
                event(new DeleteFeedable($model));
                \DB::table('collaborates')->where('id',$model->id)->update(['state'=>Collaborate::$state[2]]);
            }
        });

        \DB::table("collaborates")->whereRaw('deleted_at < expires_on')->whereNotNull('deleted_at')->orderBy('id')->chunk(100,function($models){
            foreach($models as $model){
                \DB::table('collaborates')->where('id',$model->id)->update(['state'=>Collaborate::$state[1]]);
            }
        });

    }
}
