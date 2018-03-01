<?php

namespace App\Console\Commands;

use App\Profile;
use Illuminate\Console\Command;

class SetNotificationSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SetNotificationSettings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates entries in Notification_settings for all users.';

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
        Profile::whereNull('deleted_at')->orderBy('id')->chunk(100, function($models){
            foreach ($models as $model) {

            }
        });
    }
}
