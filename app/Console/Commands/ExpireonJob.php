<?php
namespace App\Console\Commands;
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
        //this run only once after that remove from kernel.php this file
        \DB::table("jobs")->where('expires_on','<=',Carbon::now()->toDateTimeString())->whereNull('deleted_at')
            ->update(['deleted_at'=>Carbon::now()->toDateTimeString()]);

        \DB::table("jobs")->where('expires_on','>=',Carbon::now()->addDays(1)->toDateTimeString())
            ->where('expires_on','<=',Carbon::now()->addDays(2)->toDateTimeString())->whereNull('deleted_at')->orderBy('id')->chunk(100,function($models){
            foreach($models as $model){
               $profileId = $model->profile_id;
               $companyId = $model->company_id;
            }
        });

    }
}
