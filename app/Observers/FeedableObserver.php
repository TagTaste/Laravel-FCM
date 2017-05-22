<?php namespace App\Observers;

use App\Events\NewFeedable;
use App\Events\UpdateFeedable;
use App\Events\DeleteFeedable;

class FeedableObserver {
    
    public function created($model)
    {
        event(new NewFeedable($model));
    }
    
    //we don't need to listen on model because we just store the redis keys as the payload
    //So the updates to the model need not update the feedable.
    
//    public function updated($model)
//    {
//        //event(new UpdateFeedable($model));
//    }
    
    public function deleted($model)
    {
        event(new DeleteFeedable($model));
    }
    
    
}
