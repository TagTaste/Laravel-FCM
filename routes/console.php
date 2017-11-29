<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

\Artisan::command("recipe:delete {recipeId}",function($recipeId){
    $recipe = \App\Recipe::find($recipeId);
    $recipe->delete();
});

\Artisan::command("job:delete {jobId}",function($jobId){
    $job = \App\Job::find($jobId);
    $job->delete();
});