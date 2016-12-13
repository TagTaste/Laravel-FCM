<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \Form;

class ViewComponents extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Form::component('bsText','components.form.text',['label','name','value','attributes']);
        Form::component('bsFile','components.form.file',['label','name']);
        Form::component('bsTextarea','components.form.textarea',['label','name','value','attributes']);
        Form::component('bsCheckbox','components.form.checkbox',['label','name','value','attributes']);
        Form::component('bsSubmit','components.form.submit',['value']);
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
