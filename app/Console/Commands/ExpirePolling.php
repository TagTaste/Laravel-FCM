<?php

namespace App\Console\Commands;

use App\Events\ExpirePoll;
use App\Recipe\Profile;
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

        \App\Polling::where('created_at', '<=', Carbon::now()->subDay(7)->toDateTimeString())
            ->where('is_expired', 0)->whereNull('deleted_at')
            ->orderBy('id')->chunk(100, function ($models) {

                foreach ($models as $model) {
                    echo $model->id . "\n";
                    // var_dump($model->update(['expired_time' => Carbon::now()->toDateTimeString(), 'is_expired' => 1]));
                    $profiles =  Profile::select('profiles.*')->join('poll_votes', 'poll_votes.profile_id', '=', 'profiles.id')
                        ->where('poll_votes.poll_id', $model->id)->get();
                    $admin = Profile::where('id', $model->profile_id)->first();
                    $profiles->push($admin);
                    
                    foreach ($profiles as $profile) {
                        $event = $model;
                        if($admin->id==$profile->id){
                            
                            $event->isAdmin = true;
                        }
                        $event->profile = $profile;

                        event(new ExpirePoll($event,$admin));
                    }
                }
            });
    }
}
