<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if($this->app->environment('testing') || $this->app->environment('local')){
            if(class_exists('Laralib\L5scaffold\GeneratorsServiceProvider')){
                $this->app->register('Laralib\L5scaffold\GeneratorsServiceProvider');
            }
        }
    
        if(env('LOG_QUERY') === 1){
            \DB::listen(function ($query) {
            \Log::info([
                $query->sql,
                $query->bindings,
                $query->time
            ]);
        });
        }

    }
}
