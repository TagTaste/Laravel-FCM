<?php
namespace App\Console\Commands;
use App\Events\DeleteFeedable;
use Carbon\Carbon;
use Illuminate\Console\Command;
class ExpirePolling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expires_on:polling';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'set deleted_at in when poll is expired';
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
        \App\Polling::with([])->where('updated_at','<=',Carbon::now()->subDay(7)->toDateTimeString())
            ->where('is_expired',0)->whereNull('deleted_at')
            ->orderBy('id')->chunk(100,function($models) {
                foreach($models as $model){
                    echo $model->id . "\n";
                    $model->update(['deleted_at'=>Carbon::now()->toDateTimeString(),'expired_time'=>Carbon::now()->toDateTimeString(),'is_expired'=>1]);
                    $model->removeFromCache();
                }
            });

    }
}
