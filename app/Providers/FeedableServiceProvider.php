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
        \App\Photo::observe(FeedableObserver::class);
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
