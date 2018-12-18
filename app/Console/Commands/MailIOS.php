<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\MailIOSJob;

class MailIOS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:ios';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        //
    $profiles = \DB::table('profiles')->join('users','profiles.user_id','=','users.id')->join('app_info','profiles.id','=','app_info.profile_id')->where('app_info.platform','ios')->get();
        foreach ($profiles as $profile) {
             $mail = (new MailIOSJob($profile->email,$profile->name))->onQueue('emails');
             dispatch($mail);
            $this->info($profile->name);
        }

    }
}
