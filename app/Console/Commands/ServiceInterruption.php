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
            ->whereIn('id',[23,24,25,26,32,35,39,44,45,49,59,68,69,80,88,101,137,162,203,238,240,256,295,322,334,359,368,369,377,383,398,480,507,570,643,666,677,771,844,880,946,975,1001,1008,1106,1160,1355,1387,1417,1460,1500,1799,2561,2723,2969,3043,3084,3090,3501,3593,3770,4029,4311,4363,4378,4768,4869,4939,5194,5445,5515,5595,5912,6163,6712,6719,6722,7118,7215,7561,7938,8153,8177,8656,8702,8759,9156,9272,9568,9669,9678,9711,9752,9763,9767,9781,9940,9965,10170,10225,10354,10508,10715,10720,10771,10785,10787,10834,10882,10932,11040,11157,11176,11181,11183,11225,11228,11236,11290,11401,11538,11569,11570,11571,11580,11600,11616,11740,11853,11960,11962,11967,11976,12368,12388,12419,12428,12436,12519,12523,12805,12852,12904,12930,12961,12964,12982,13001,13199,13222,13231,13247,13267,13301,13311,13327,13348,13361,13401,13405,13610,13611,13701,13749,13771,13812,13957,13961,13969,13970,13994,14101,14125,14131,14142,14156,14159,14160,14161,14162,14167,14168,14169,14171,14174,14186,14188,14192,14197,14205,14208,14229,14235,14237,14238,14250])
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
