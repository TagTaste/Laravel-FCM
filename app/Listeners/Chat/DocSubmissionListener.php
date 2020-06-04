<?php

namespace App\Listeners\Chat;

use App\Events\DocSubmissionEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DocSubmissionListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  DocSubmissionEvent  $event
     * @return void
     */
    public function handle(DocSubmissionEvent $event)
    {
        //
    }
}
