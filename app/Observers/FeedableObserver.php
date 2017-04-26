<?php namespace App\Observers;

use App\Events\NewFeedable;
use App\Events\UpdateFeedable;
use App\Listeners\DeleteFeedable;

class FeedableObserver {
    
    public function created($model)
    {
        event(new NewFeedable($model));
    }
    
    public function updated($model)
    {
        event(new UpdateFeedable($model));
    }
    
    public function deleting($model)
    {
        event(new DeleteFeedable($model));
    }
    
    
}
