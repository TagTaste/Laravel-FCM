<?php namespace App\Observers;

class FeedableObserver {
    
    public function created($model)
    {
        //todo: check for privacy
        
        if(!method_exists($model,'profile')){
           throw new \Exception("Profile relationship not defined on Feedable " . $model);
        }
        
        $model->profile->pushToMyFeed($model);
    }
}
