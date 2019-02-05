<?php

namespace App\Subscribers;


use App\CompanyUser;
use App\Events\Actions\Comment;
use App\Events\Actions\Like;
use App\ModelSubscriber;
use App\Notify\Profile;
use App\PublicReviewProduct\Review;
use Illuminate\Support\Facades\Notification;

class Actions
{
    public function notifySubscribers($event)
    {
        ModelSubscriber::updateSubscriberTimestamp($event->model,$event->model->id,$event->who['id']);
    }
    
    public function addOrUpdateSubscriber($event)
    {
        $modelId = $event->model->id;
        $model = get_class($event->model);
        $profilesIds = Profile::select('profiles.*')
            ->join('model_subscribers','model_subscribers.profile_id','=','profiles.id')
            ->where('model_subscribers.model','=',$model)
            ->where('model_subscribers.model_id','=',$modelId)
            ->where('model_subscribers.profile_id','!=',$event->who['id'])
            ->whereNull('muted_on')
            ->whereNull('model_subscribers.deleted_at')->get()->pluck('id');
        \Log::info($profilesIds);

        // Adding company admins
        if($event->model->company_id){
            $companyAdminIds = CompanyUser::where('company_id',$event->model->company_id)
                ->whereNotIn('profile_id',$profilesIds)->get()->pluck('profile_id');

            if(count($companyAdminIds)){
                $profilesIds = $profilesIds->merge($companyAdminIds);
            }
        }
        if($model == 'App\PublicReviewProduct\Review')
        {
            $reviewProfileIds = Review::where('id',$modelId)->first()->pluck('profile_id');
            $profilesIds = $profilesIds->merge($reviewProfileIds);
        }
        \Log::info($profilesIds);
        $profiles = Profile::whereIn('id',$profilesIds)->get();

        //send notification
        if($profiles->count() === 0) {
            \Log::info("No model subscribers. Not sending notification.");
            return;
        }
        $class = "\App\Notifications\Actions\\" . ucwords($event->action);
        Notification::send($profiles, new $class($event));
    }
    
    public function likeaddOrUpdateSubscriber($event)
    {
        $class = "\App\Notifications\Actions\\" . ucwords($event->action);
        $profiles = null;
        if($event->model->company_id){
            $companyUsers = CompanyUser::where('company_id',$event->model->company_id)->select("profile_id")->get();
            
            if($companyUsers->count()){
                $profiles = Profile::whereIn('id',$companyUsers->pluck('profile_id'))->get();
            }
        }
        
        //send only to the creator, if not a company model.
        if(!$profiles){
            $profiles = Profile::where('id',$event->model->profile_id)->first();
        }
        
        Notification::send($profiles, new $class($event));
    }
    
    public function subscribe($events)
    {

        $events->listen(
            [
//                Like::class,
                Comment::class
            ],
            'App\Subscribers\Actions@notifySubscribers');
        
        $events->listen(
            [
                Comment::class
            ],
            'App\Subscribers\Actions@addOrUpdateSubscriber');
        
        $events->listen(
            [
                Like::class,
            ],
            'App\Subscribers\Actions@likeaddOrUpdateSubscriber');
    }
}