<?php

namespace App\Console\Commands;

use App\Jobs\ServiceInterruptionJob;
use App\Profile;
use App\User;
use Illuminate\Console\Command;

class CompleteTastingMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CompleteTastingMail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send mail for completion of tasting process mail';

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
        $profileIds = \DB::table('collaborate_tasting_user_review')->where('current_status',3)->get()->pluck('profile_id');
        $userIds = Profile::whereIn('id',$profileIds)->get()->pluck('user_id');
        User::with([])->whereIn('id',$userIds)->whereNull('deleted_at')
            ->orderBy('id')->chunk(100,function($models) {
                $count = 0;
                foreach ($models as $model)
                {
                    $count++;
                    echo "name of taster ".$model->name."\n";
                    $mail = (new ServiceInterruptionJob($model->email,$model->name))->onQueue('emails');
                    \Log::info('Queueing send invitation...');
                    dispatch($mail);
                    echo "no is ".$count."\n";
                }
            });
    }
}
