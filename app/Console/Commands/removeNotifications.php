<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class removeNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:remove-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove Notification of desired period';

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
        $getDate = date("Y-m-d H:i:s",strtotime("-".config("constant.NOTIFICATION_DELETE_PERIOD")." days"));
        return  DB::table("notifications")->whereNotNull("read_at")->where("created_at","<",$getDate)->delete();
    }
}
