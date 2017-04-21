<?php

namespace App\Providers;

use App\Observers\FeedableObserver;

use Illuminate\Support\ServiceProvider;

class FeedableServiceProvider extends ServiceProvider
{
    /**
     * Namespaced class names which would appear on feeds.
     *
     * @var array
     */
    private $feedables = [
        \App\Recipe::class,
        \App\Photo::class,
    ];
    
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if(empty($this->feedables)){
            return;
        }
        
        $this->setFeedableObserver();
    }
    
    /**
     * Assigns observers to all $feedables.
     */
    private function setFeedableObserver()
    {
        foreach($this->feedables as $feedable){
            $feedable::observe(FeedableObserver::class);
        }
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
