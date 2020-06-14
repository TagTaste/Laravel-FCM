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
            ->whereIn('id',[ 13,18,23,24,26,32,35,39,44,45,59,68,69,80,88,101,165,240,322,368,377,383,387,507,570,666,677,771,844,946,975,1008,1106,1160,1460,1500,1507,1799,2561,3043,3084,3501,3593,3770,3942,4029,4311,4535,4678,4768,4869,5194,5445,5595,5803,5912,5976,6163,6719,6722,7118,7215,7561,7938,8177,8656,8702,8759,9156,9272,9307,9568,9669,9711,9749,9763,9764,9767,9781,9893,9908,9965,10225,10354,10508,10715,10720,10771,10785,10994,11040,11157,11181,11183,11228,11401,11569,11581,11600,11614,11740,11747,11752,11757,11774,11798,11851,11852,11853,11960,11962,11967,11976,12152,12368,12388,12419,12623,12852,12904,12964,13208,13209,13213,13219,13222,13223,13224,13225,13231,13234,13267,13348,13373,13405,13491,13611,13701,13812,13957,13961,13965,13969,13970,13991,13994,14006,14014,14024,14072,14101,14125,14131,14140,14142,14144,14150,14156,14157,14159,14160,14161,14162,14163,14165,14167,14168,14169,14171,14174,14178,14179,14180,14181,14184,14186,14188,14192,14197,14200,14201,14205,14208,14224,14230,14238,14250,14252,14253,14254,14255,14256])
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
