<?php

namespace App\Providers;

use App\Observers\FeedableObserver;

use Illuminate\Support\ServiceProvider;
class FeedableServiceProvider extends ServiceProvider
{
    
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setFeedableObserver();
    }
    
    /**
     * Assigns observers to all $feedables.
     */
    private function setFeedableObserver()
    {
        \App\Recipe::observe(FeedableObserver::class);
        
        //dont add photos here.
        //laravel doesn't fire created event on pivot.
        
        \App\Shoutout::observe(FeedableObserver::class);
        \App\Job::observe(FeedableObserver::class);
        \App\Collaborate::observe(FeedableObserver::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
