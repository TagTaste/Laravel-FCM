<?php

namespace App\Console\Commands;

use App\Collaborate;
use App\CompanyUser;
use App\Events\DeleteFeedable;
use App\Job;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CollaborationExpiresOnUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SetExpireon:Collab';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'set expires_on in collaboration';

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

            Collaborate::where('state', '=', 1)->whereNull('deleted_at')->where(function($q){
                $q->orWhere("expires_on","=",null);
                $q->orWhere("expires_on","=","");
            })
            ->orderBy('id')->chunk(100, function ($models) {
                foreach ($models as $model) {
                    $updated_at =   date("Y-m-d H:i:s",strtotime('+1 month', strtotime($model->updated_at)));
                    echo $model->id.PHP_EOL;
                    // continue;
            
                    // dd($updated_at);
                    \DB::table('collaborates')->where('id', $model->id)->update(['expires_on' => $updated_at]);
                    
                }
            });
    }
}
