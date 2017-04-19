<?php

namespace App\Providers;

use App\Observers\FeedableObserver;
use App\Recipe;
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
        Recipe::observe(FeedableObserver::class);
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
