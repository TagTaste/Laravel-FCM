<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FeedbackMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $feedbackEmail;
    protected $feedbackInfo;
    public function __construct($feedbackInfo)
    {
        $this->feedbackEmail = 'feedback@tagtaste.com';
        $this->feedbackInfo = $feedbackInfo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = ["feedbackInfo"=>$this->feedbackInfo];
        \Mail::send('feedback.feedback', $data, function($message)
        {
            $message->to($this->feedbackEmail, 'Admin')->cc('tech@tagtaste.com')->subject('feedback info!');
        });
    }
}
