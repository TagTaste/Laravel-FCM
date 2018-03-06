<?php

namespace App\Console\Commands;

use App\Collaborate;
use App\Company;
use App\Events\NewFeedable;
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
        $jobIds = [];
        //this run only once after that remove from kernel.php this file
        \DB::table("jobs")->where('state',Job::$state[2])->whereIn('id',$jobIds)->orderBy('id')->chunk(100,function($models){
            foreach($models as $model){
                $profile = \App\Profile::find($model->profile_id);
                if($model->comapny_id != null)
                {
                    $company = Company::find($model->comapny_id);
                    event(new NewFeedable($model, $company));
                }
                else
                {
                    $profile = \App\Profile::find($model->profile_id);
                    event(new NewFeedable($model, $profile));
                }

                //push to feed


                //add subscriber
                event(new \App\Events\Model\Subscriber\Create($model,$profile));

                \App\Filter\Collaborate::addModel($model);

                \DB::table('jobs')->where('id',$model->id)->update(['state'=>Job::$state[0],'deleted_at'=>null,'expires_on'=>Carbon::now()->addMonth()->toDateTimeString()]);
            }
        });

        $collabIds = [86];
        Collaborate::where('state',Collaborate::$state[2])->whereIn('id',$collabIds)->orderBy('id')->chunk(100,function($models){
            foreach($models as $model){

                $profile = \App\Profile::find($model->profile_id);
                event(new NewFeedable($model, $profile));
                echo "yaha";

                //push to feed


                //add subscriber
                event(new \App\Events\Model\Subscriber\Create($model,$profile));

                echo "yaha 1";

                \App\Filter\Collaborate::addModel($model);

                echo "yaha 2";

                $st = \DB::table('collaborates')->where('id',$model->id)->update(['state'=>Collaborate::$state[0],'deleted_at'=>null,'expires_on'=>Carbon::now()->addMonth()->toDateTimeString()]);

                echo $st;
            }
        });

    }
}
