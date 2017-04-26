<?php namespace App\Observers;

use App\Events\NewFeedable;

class FeedableObserver {
    
    public function created($model)
    {
        event(new NewFeedable($model));
    }
    
    public function updated($model)
    {
        if(method_exists($model,'payload')){
            $model->payload->update(['payload'=>$model]);
        }
        
    }
}
