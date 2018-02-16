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
        $notifications = \DB::table('notifications')
            ->whereRaw('json_extract(data, \'$.model\') = CAST(\'null\' as JSON) AND json_extract(data, \'$.action\') = \'joinfriend\'')
            ->get();
        foreach ($notifications as $notification) {
            $data = json_decode($notification->data);
            $profile = \App\Profile::find($data->profile->id);
            if(!$profile)
                continue;
            $data->model = $profile->getNotificationContent();
            \DB::table('notifications')->where('id', $notification->id)->update(['data' => json_encode($data)]);
            echo "Updated id: $notification->id\n";
        }
    }
}
