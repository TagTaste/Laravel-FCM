<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CollaborationReportUpload
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $collaborate;
    public $action;
    public $content;
    public $notificationMode;
    public function __construct($collaborate = null, $content = null, $notificationMode)
    {
        $this->collaborate = $collaborate;
        $this->content = $content;
        $this->notificationMode = $notificationMode;
        $this->action = 'collaborate_report_upload';
        file_put_contents(storage_path("logs") . "/skynet_test.txt", "\nEvent called : CollaborationReportUpload", FILE_APPEND);
    }
}
