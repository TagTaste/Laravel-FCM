<?php

namespace App\Console\Commands;

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
    protected $description = 'Change Date Format in Profile';

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
        \DB::table("jobs")->orderBy('id')->chunk(100,function($models){
            foreach($models as $model){
                \DB::table('jobs')->where('id',$model->id)->update(['expires_on'=>(Carbon::parse($model->created_at))->addMonth()->toDateTimeString()]);
            }
        });

        \DB::table("collaborates")->orderBy('id')->chunk(100,function($models){
            foreach($models as $model){
                \DB::table('collaborates')->where('id',$model->id)->update(['expires_on'=>(Carbon::parse($model->created_at))->addMonth()->toDateTimeString()]);
            }
        });

    }
}
