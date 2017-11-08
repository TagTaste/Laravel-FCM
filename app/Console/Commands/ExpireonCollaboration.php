<?php
namespace App\Console\Commands;
use App\CompanyUser;
use App\Job;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
class ExpireonCollaboration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expires_on:collaboration';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'set deleted_at in when collaboration is expired';
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
        $count =0;
        \Log::info("collab ".$count);
        $count++;
        //this run only once after that remove from kernel.php this file
        \DB::table("collaborates")->where('expires_on','<=',Carbon::now()->toDateTimeString())->whereNull('deleted_at')
            ->update(['deleted_at'=>Carbon::now()->toDateTimeString()]);

        \App\Collaborate::with([])->where('expires_on','>=',Carbon::now()->addDays(1)->toDateTimeString())
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

        \App\Collaborate::with([])->where('expires_on','>=',Carbon::now()->toDateTimeString())
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

        \App\Collaborate::with([])->where('expires_on','>=',Carbon::now()->addDays(7)->toDateTimeString())
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
