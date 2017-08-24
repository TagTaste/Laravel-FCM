<?php

namespace App\Providers\Meta;

use App\Company;
use App\Observers\ModelCountObserver;
use Illuminate\Support\ServiceProvider;

class ModelCount extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->getModelCountsFor();
    }

    private function getModelCountsFor(){
        \App\Profile::observe(ModelCountObserver::class);
        \App\Company::observe(ModelCountObserver::class);
        
        \App\Shoutout::observe(ModelCountObserver::class);
        \App\Job::observe(ModelCountObserver::class);
        \App\Collaborate::observe(ModelCountObserver::class);
        \App\Recipe::observe(ModelCountObserver::class);
        \App\Photo::observe(ModelCountObserver::class);
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
