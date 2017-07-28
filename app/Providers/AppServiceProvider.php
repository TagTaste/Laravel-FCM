<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if($this->app->environment('testing') || $this->app->environment('local')){
            $this->app->register('Laralib\L5scaffold\GeneratorsServiceProvider');
        }
    
        \DB::listen(function ($query) {
            \Log::info([
                $query->sql,
                $query->bindings,
                $query->time
            ]);
        });
    }
}
