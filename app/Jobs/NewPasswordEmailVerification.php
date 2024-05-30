<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NewPasswordEmailVerification implements ShouldQueue
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
        switch ($this->mailDetails['mail']) {
            case 'create_password':
                $subject = "TagTaste: Create Your Password";
                $heading = "Create Your Password";
                $firstParagraph = "To create your password for your TagTaste account, please use the OTP provided below.";
                break;
            case 'change_password':
                $subject = "TagTaste: Change Your Password";
                $heading = "Change Your Password";
                $firstParagraph = "We received a request to change the password for your TagTaste account. Please use the OTP provided below to proceed with changing your password.";
                break;
            case 'forgot_password':
                $subject = "TagTaste: Reset Your Password";
                $heading = "Reset Your Password";
                $firstParagraph = "We received a request to reset the password for your TagTaste account. Please use the OTP provided below to reset your password.";
                break;
        };

        $data = ["otp" => $this->mailDetails['otp'], "name" => $this->mailDetails['username'], "heading" => $heading, "firstParagraph" => $firstParagraph];
        \Mail::send('emails.new-password-verify-email', $data, function($message) use ($subject)
        {
            $message->to($this->mailDetails['email'], $this->mailDetails['username'])->subject($subject);
        });
    }
}