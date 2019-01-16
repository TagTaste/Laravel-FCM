<?php

namespace App\Listeners;

use App\CompanyUser;
use App\Events\Action;
use App\ModelSubscriber;
use App\Notify\Profile;
use \Illuminate\Support\Facades\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifySubscribers
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
     * @param  Action  $event
     * @return void
     */
    public function handle(Action $event)
    {
        $modelId = $event->model->id;
        $model = $event->getModelName();
        $content = $event->content;
        $profiles = Profile::select('profiles.*')->join('model_subscribers','model_subscribers.profile_id','=','profiles.id')
                    ->where('model_subscribers.model','like',$model)
                    ->where('model_subscribers.model_id','=',$modelId)
                    ->whereNull('muted_on')
                    ->whereNull('model_subscribers.deleted_at')->get();
        $class = "\App\Notifications\Actions\\" . ucwords($event->action);

        // Adding other company admins
        if(isset($model->company_id) && !is_null($model->company_id)) {
            $adminProfilesIds = CompanyUser::where("company_id",$model->company_id)->get();
            $ids = [];
            foreach ($adminProfilesIds as $id) {
                $ids[] = $id->profile_id;
            }
            $adminProfiles = Profile::whereIn('id', $ids)->get();
            $profiles->merge($adminProfiles);
        }
        Notification::send($profiles, new $class($model,$modelId,$event->action));
    }
}
