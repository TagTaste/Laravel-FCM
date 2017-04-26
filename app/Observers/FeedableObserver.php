<?php namespace App\Observers;

use App\Events\NewFeedable;
use App\Events\UpdateFeedable;
use App\Events\DeleteFeedable;

class FeedableObserver {
    
    public function created($model)
    {
        event(new NewFeedable($model));
    }
    
    public function updated($model)
    {
        event(new UpdateFeedable($model));
    }
    
    public function deleted($model)
    {
        \Log::info('deleting');
        \Log::info($model);
        event(new DeleteFeedable($model));
    }
    
    
}
