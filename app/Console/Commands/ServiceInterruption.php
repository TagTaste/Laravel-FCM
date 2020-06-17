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
            ->whereIn('id',[13,124,137,162,238,369,728,4363,4547,6712,10882,11176,11290,12875,12982,13001,14274,14275,14276,14277,14278,14279])
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
