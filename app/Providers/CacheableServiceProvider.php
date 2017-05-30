<?php

namespace App\Providers;

use App\Observers\CacheableObeserver;
use Illuminate\Support\ServiceProvider;

class CacheableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->thingsToCache();
    }
    
    private function thingsToCache()
    {
        \App\Collaborate::observe(CacheableObeserver::class);
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
