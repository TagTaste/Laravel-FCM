<?php namespace App\Observers;

class FeedableObserver {
    
    public function created($model)
    {
        //todo: check for privacy
        
        if(!method_exists($model,'profile')){
            return;
        }
        
        $model->profile->pushToMyFeed($model);
    }
}
