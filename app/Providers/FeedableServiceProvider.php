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
//        \App\Photo::observe(FeedableObserver::class);
        
        \App\Shoutout::observe(FeedableObserver::class);
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
