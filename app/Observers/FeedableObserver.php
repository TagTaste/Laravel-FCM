<?php namespace App\Observers;

use App\Events\NewFeedable;

class FeedableObserver {
    
    public function created($model)
    {
        event(new NewFeedable($model));
    }
}
