<?php

namespace App\Console\Commands;

use function foo\func;
use Illuminate\Console\Command;

class SocialConnectedAddFlag extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SocialConnectedAddFlag';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update flag social connection';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \DB::table('profiles')->whereNull('deleted_at')->orderBy('id')->chunk(100, function ($models) {
            foreach ($models as $model) {
                if(isset($model->facebook_url) && !is_null($model->facebook_url))
                    \DB::table('profiles')->where('id', $model->id)->update(['is_facebook_connected'=>1]);
                if(isset($model->linkedin_url) && !is_null($model->linkedin_url))
                    \DB::table('profiles')->where('id', $model->id)->update(['is_linkedin_connected'=>1]);
                if(isset($model->google_url) && !is_null($model->google_url))
                    \DB::table('profiles')->where('id', $model->id)->update(['is_google_connected'=>1]);
            }
        });
    }
}
