<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Events\Action;

class CollaborationReportUpload
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $model;
    public $collaborate;
    public $action;
    public $content;
    public $notificationMode;

    public function __construct(Model &$model, $content = null, $notificationMode)
    {
        $this->model = $model;
        $this->collaborate = $model;
        $this->content = $content;
        $this->notificationMode = $notificationMode;
        $this->action = 'collaborate_report_upload';
        file_put_contents(storage_path("logs") . "/skynet_test.txt", "\nEvent called : CollaborationReportUpload", FILE_APPEND);
    }
}
