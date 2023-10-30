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
    protected $mailDetails;
    
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
        $data = ["otp" => $this->mailDetails['otp'], "name" => $this->mailDetails['username']];
        \Mail::send('emails.verify-email', $data, function($message)
        {
            $message->to($this->mailDetails['email'], $this->mailDetails['username'])->subject('Verify your email');
        });
    }
}
