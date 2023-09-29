<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SignupEmailOtpVerification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $mailDetails;
    
    public function __construct($mailDetails)
    {
        $this->mailDetails = $mailDetails;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = ["otp" => $this->mailDetails->otp];
        \Mail::send('emails.signup-verify-email.blade', $data, function($message)
        {
            $message->to($this->mailDetails->email, $this->mailDetails->username)->subject('Verify your email');
        });
    }
}
