<?php
namespace App\Console\Commands;
use App\Application;
use App\CompanyUser;
use App\Job;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
class ExpireonJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expires_on:job';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'set deleted_at in when job is expired';
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

        \App\Job::with([])->where('expires_on','<=',Carbon::now()->toDateTimeString())->whereNull('deleted_at')
            ->orderBy('id')->chunk(100,function($models) {
                foreach ($models as $model) {
                    $model->delete();
                    //send notificants to applicants for delete job
                    $profileIds = Application::where('job_id',$model->id)->get()->pluck('profile_id');
                    foreach ($profileIds as $profileId)
                    {
                        $model->profile_id = $profileId;
                        event(new \App\Events\Actions\ExpireModel($model));
                    }
                }
            });

        \App\Job::with([])->where('expires_on','>=',Carbon::now()->addDays(1)->toDateTimeString())
            ->where('expires_on','<=',Carbon::now()->addDays(2)->toDateTimeString())->whereNull('deleted_at')->orderBy('id')->chunk(100,function($models){
            foreach($models as $model){
               $companyId = $model->company_id;
               if(isset($companyId))
               {
                   $profileIds = CompanyUser::where('company_id',$companyId)->get()->pluck('profile_id');
                   foreach ($profileIds as $profileId)
                   {
                       $model->profile_id = $profileId;
                       event(new \App\Events\Actions\Expire($model));

                   }
               }
               else {
                   event(new \App\Events\Actions\Expire($model));
               }
            }


        });

        \App\Job::with([])->where('expires_on','>=',Carbon::now()->toDateTimeString())
            ->where('expires_on','<=',Carbon::now()->addDays(1)->toDateTimeString())->whereNull('deleted_at')->orderBy('id')->chunk(100,function($models){
                foreach($models as $model){
                    $companyId = $model->company_id;
                    if(isset($companyId))
                    {

                        $profileIds = CompanyUser::where('company_id',$companyId)->get()->pluck('profile_id');
                        foreach ($profileIds as $profileId)
                        {
                            $model->profile_id = $profileId;
                            event(new \App\Events\Actions\Expire($model));

                        }
                    }
                    else {
                        event(new \App\Events\Actions\Expire($model));
                    }
                }


            });

        \App\Job::with([])->where('expires_on','>=',Carbon::now()->addDays(7)->toDateTimeString())
            ->where('expires_on','<=',Carbon::now()->addDays(8)->toDateTimeString())->whereNull('deleted_at')->orderBy('id')->chunk(100,function($models){
                foreach($models as $model){
                    $companyId = $model->company_id;
                    if(isset($companyId))
                    {
                        $profileIds = CompanyUser::where('company_id',$companyId)->get()->pluck('profile_id');
                        foreach ($profileIds as $profileId)
                        {
                            $model->profile_id = $profileId;
                            event(new \App\Events\Actions\Expire($model));

                        }
                    }
                    else {
                        event(new \App\Events\Actions\Expire($model));
                    }
                }


            });

    }
}
