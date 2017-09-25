<?php

namespace App\Listeners;

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
    private $column = "_id";

    private function setColumn(&$modelName)
    {
        $this->column = $modelName . $this->column;
    }

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
            $response = $event->model->payload->delete();
            $class = "\\App\\Shareable\\" . ucwords($event->modelName);
            $this->setColumn($event->modelName);
            $model = $class::where($this->column, $event->modelId)->where('profile_id', $event->model->profile_id)->whereNull('deleted_at')->first();

            if ($model) {
                $model->delete();

            }

        }
    }
}
