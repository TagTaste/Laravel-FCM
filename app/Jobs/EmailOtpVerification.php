<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EmailOtpVerification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $otp;
    
    public function __construct($otp)
    {
        $this->otp = $otp;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = ["otp" => $this->otp];
        \Mail::send('emails.verify-mail', $data, function($message)
        {
            $message->to($this->user->email, $this->user->name)->subject('Verify your email');
        });
    }
}
