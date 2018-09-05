<?php

namespace App\Listeners;

use App\Events\UploadQuestionEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Artisan;


class UploadQuestionListener implements ShouldQueue
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
     * @param  UploadQuestionEvent  $event
     * @return void
     */
    public function handle(UploadQuestionEvent $event)
    {
        $this->model = Artisan::call("Collaboration:Question", ['id'=>$event->collaborateId,'global_question_id'=>$event->globalQuestionId]);

    }
}
