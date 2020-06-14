<?php

namespace App\Console\Commands;

use App\Jobs\ServiceInterruptionJob;
use App\User;
use Illuminate\Console\Command;

class ServiceInterruption extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ServiceInterruptionMail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send mail for interruption mail';

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
        User::with([])->whereNull('deleted_at')
            ->whereNotNull('verified_at')
            ->whereIn('id',[ 13,26,88,677,1460,3084,5803,8702,8759,9272,9669,9965,10785,13701,13994,14006,14224,14230,14238,14250,14252,14253,14254,14255,14256,14257,14258,14260,14262,14263,14264,14267,14269,14270,14272])
            ->orderBy('id')->chunk(100,function($models) {
                $count = 0;
                foreach ($models as $model)
                {
                    $count++;
                    \Log::info($model->name);
                    $mail = (new ServiceInterruptionJob($model->email,$model->name))->onQueue('emails');
                    \Log::info('Queueing send invitation...');
                    dispatch($mail);
                    echo "no is ".$count." user id ".$model->id."\n";
                }
            });
    }
}
