<?php

namespace App\Console\Commands;

use App\Collaborate;
use App\CompanyUser;
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
        Job::where('expires_on','<=',Carbon::now()->toDateTimeString())->where('id',20)->whereNull('deleted_at')->orderBy('id')->chunk(100,function($models){
            foreach($models as $model){
                $companyId = $model->company_id;
                if(isset($companyId))
                {
                    $profileIds = CompanyUser::where('company_id',$companyId)->get()->pluck('profile_id');
                    foreach ($profileIds as $profileId)
                    {
                        $model->profile_id = $profileId;
                        event(new \App\Events\Actions\ExpireModel($model));
                    }
                }
                else {
                    event(new \App\Events\Actions\ExpireModel($model));
                }
//                \DB::table('jobs')->where('id',$model->id)->update(['state'=>Job::$state[2]]);
                \App\Filter\Job::removeModel($model->id);
                event(new DeleteFeedable($model));
                $model->update(['deleted_at'=>Carbon::now()->toDateTimeString(),'state'=>Job::$state[2]]);

                \Log::info("sending mail");

            }
        });

        \DB::table("jobs")->whereRaw('deleted_at < expires_on')->whereNotNull('deleted_at')->orderBy('id')->chunk(100,function($models){
            foreach($models as $model){
                \DB::table('jobs')->where('id',$model->id)->update(['state'=>Job::$state[1]]);
            }
        });

        Collaborate::where('expires_on','<=',Carbon::now()->toDateTimeString())->where('id',16)->whereNull('deleted_at')->orderBy('id')->chunk(100,function($models){
            foreach($models as $model){
                $companyId = $model->company_id;
                if(isset($companyId))
                {
                    $profileIds = CompanyUser::where('company_id',$companyId)->get()->pluck('profile_id');
                    foreach ($profileIds as $profileId)
                    {
                        $model->profile_id = $profileId;
                        event(new \App\Events\Actions\ExpireModel($model));
                    }
                }
                else {
                    event(new \App\Events\Actions\ExpireModel($model));
                }
                event(new DeleteFeedable($model));
                event(new \App\Events\DeleteFilters(class_basename($model),$model->id));
                $model->update(['deleted_at'=>Carbon::now()->toDateTimeString(),'state'=>Collaborate::$state[2]]);
                \Log::info("sending mail");
            }
        });

//        \DB::table("collaborates")->whereRaw('deleted_at < expires_on')->whereNotNull('deleted_at')->orderBy('id')->chunk(100,function($models){
//            foreach($models as $model){
//                \DB::table('collaborates')->where('id',$model->id)->update(['state'=>Collaborate::$state[1]]);
//            }
//        });

    }
}
