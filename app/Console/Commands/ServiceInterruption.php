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
            ->whereIn('id',[ 13, 18, 23, 24, 32, 35, 39, 44, 45, 59, 68, 80, 101, 165, 240, 322, 368, 383, 387, 507, 556, 626, 637, 731, 804, 906, 935, 1066, 1120, 1460, 1467, 1759, 2521, 3003, 3461, 3730, 3902, 4271, 4495, 4638, 4728, 4829, 5154, 5555, 5872, 5935, 6122, 6678, 6681, 7174, 7520, 7896, 8135, 9112, 9263, 9523, 9666, 9704, 9718, 9722, 9736, 9847, 9862, 10177, 10460, 10667, 10672, 10723, 10946, 10992, 11109, 11133, 11135, 11353, 11521, 11533, 11552, 11566, 11692, 11699, 11704, 11726, 11750, 11803, 11804, 11912, 11914, 11919, 11928, 12104, 12320, 12371, 12575, 12804, 12916, 13159, 13160, 13164, 13170, 13173, 13174, 13175, 13176, 13182, 13185, 13218, 13299, 13324, 13356, 13442, 13562, 13763, 13908, 13912, 13916, 13920, 13921, 13942, 13965, 13975, 14023, 14052, 14076, 14082, 14091, 14095, 14101, 14107, 14108, 14110, 14111, 14112, 14113, 14114, 14116, 14119, 14120, 14122, 14125, 14129, 14130, 14131, 14132, 14135, 14137, 14143, 14148, 14151, 14152])
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
