<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\MailIOSJob;

class MailUnverifiedUserVerificationEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:unverifiedUser:verificationEmail:Trigger';

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
        $users = \App\User::whereNull('verified_at')->get();
        foreach ($users as $key => $user) {
            $this->info($user->email_token);
            $user->update(['email_token'=>str_random(15)]);
            $mail = (new \App\Jobs\EmailVerification($user))->onQueue('emails');
            dispatch($mail);
            $this->info($user->email." Processed!!");
        }
    }
}
