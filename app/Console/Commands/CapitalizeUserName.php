<?php

namespace App\Console\Commands;

use App\Profile;
use Illuminate\Console\Command;

class CapitalizeUserName extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'capitalizeUserName';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Capitalize the first letter of the name of all users and update redis';

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
        Profile::whereNull('deleted_at')->orderBy('id')->chunk(100, function ($models) {
            foreach ($models as $model) {
                echo $model->name . "\n";
                \DB::table('users')->where('id',$model->user_id)->update(['name'=>ucwords($model->name)]);
                $model->addToCache();
            }
        });
    }
}
