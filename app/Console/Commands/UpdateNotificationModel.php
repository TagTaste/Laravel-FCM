<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateNotificationModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdateNotificationModel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update notification data.model in joinFriend notifications';

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
        $notifications = \DB::table('notifications')->get();
        foreach ($notifications as $notification) {
            $data = json_decode($notification->data,true);
            $profile = isset($data['profile']) ? $data['profile'] : null;
            if(!is_null($profile))
            {
                if(isset($data['action']) && $data['action'] == 'apply')
                {
                    $text = $data['profile']['name'] ." has shown interest in your collaboration ".$data['model']['content'];
                    $data['notification'] = $text;
                    \DB::table('notifications')->where('id', $notification->id)->update(['data'=>json_encode($data,true)]);
                }
                continue;
            }
            \DB::table('notifications')->where('id', $notification->id)->delete();
            echo "Updated id: $notification->id\n";
        }
    }
}
