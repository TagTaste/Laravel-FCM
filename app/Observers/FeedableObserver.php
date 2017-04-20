<?php namespace App\Observers;

class FeedableObserver {
    
    public function created($model)
    {
        if(!method_exists($model,'profile')){
           throw new \Exception("Profile relationship not defined on Feedable " . class_basename($model));
        }
        
        $model->profile->pushToMyFeed($model);
        
        if(!method_exists($model,'privacy') || is_null($model->privacy)){
            //if Privacy is not defined on the model,
            //don't throw an Exception.
            
            //Don't push it to his network or public feed.
            \Log::warning("Privacy not defined for Feedable " . class_basename($model));
            \Log::warning("Not publishing it to network or public feed.");
            return;
        }
        if($model->privacy->isNetwork() || $model->privacy->isPublic()){
            $model->profile->pushToNetwork($model);
        }

        if($model->privacy->isPublic()){
            $model->profile->pushToPublic($model);
        }
    }
}
