<?php
namespace App\Console\Commands;
use App\Events\DeleteFeedable;
use App\CompanyUser;
use App\Payment\PaymentDetails;
use App\Quiz;
use Illuminate\Console\Command;
class ExpireQuiz extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expires_on:quiz';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'set deleted_at in when quiz is expired';
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

        if(!\Cache::add(get_class($this), true, 0.5)) {
            return false;
        }
        
        Quiz::with([])->where('expired_at','<',date("Y-m-d"))->where('state',"=",config("constant.QUIZ_STATES.ACTIVE"))->whereNull('deleted_at')
            ->orderBy('created_at')->chunk(100,function($models){
        
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
                    
                    // event(new \App\Events\DeleteFilters(class_basename($model),$model->id));
                    $model->update(['state'=>config("constant.QUIZ_STATES.EXPIRED")]);
                    PaymentDetails::where('model_id', $model->id)->update(['is_active' => 0]);
                    event(new DeleteFeedable($model));
                    //$model->removeFromGraph(); //remove node and edge from neo4j
                }
            });

    }
}
