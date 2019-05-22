<?php

namespace App\Listeners;

use App\Channel\Payload;
use App\Events\DeleteFeedable as DeleteFeedableEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteFeedable
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
     * @param  DeleteFeedable  $event
     * @return void
     */
    public function handle(DeleteFeedableEvent $event)
    {
        \Log::info("deleting payload");
        if(method_exists($event->model,'payload')){
            $class = "\\App\\Shareable\\" . ucwords(class_basename($event->model));
            \Log::info($class);
            $model = lcfirst(class_basename($event->model));
            \Log::info($model);
            $class::where($model.'_id', $event->model->id)
            ->update(['deleted_at'=>\Carbon\Carbon::now()->toDateTimeString()]);
            Payload::where("payload->$model","$model:".$event->model->id)->update(['deleted_at'=>\Carbon\Carbon::now()->toDateTimeString()]);

//            $response = $event->model->payload->delete();

        }
    }
}
