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
        User::with([])->whereNull('deleted_at')->where('id',1)
            ->orderBy('id')->chunk(100,function($models) {
                foreach ($models as $model)
                {
                    \Log::info($model->name);
                    $mail = (new ServiceInterruptionJob($model->email,$model->name))->onQueue('ServiceInterruption');
                    \Log::info('Queueing send invitation...');
                    dispatch($mail);
                }
            });
    }
}
