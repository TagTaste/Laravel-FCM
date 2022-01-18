<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetPlatformAndroid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SetPlatformAndroid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets platform as "android" in app_info table';

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
        \DB::table('app_info')->whereNull('platform')->orderBy('id')->chunk(100, function ($models) {
           foreach ($models as $model) {
               \DB::table('app_info')->where('id', $model->id)->update(['platform'=> 'android']);    
               file_put_contents(storage_path("logs") . "/notification_test.txt", "Command : setting platform android in app info for profile id : ".$model->profile_id, FILE_APPEND);
               file_put_contents(storage_path("logs") . "/notification_test.txt", "------------------------\n", FILE_APPEND);       
            }
            file_put_contents(storage_path("logs") . "/notification_test.txt", "++++++++++++++++++++++++++++++++\n\n", FILE_APPEND);       

        });
        echo "\nDone...\n";
    }
}
