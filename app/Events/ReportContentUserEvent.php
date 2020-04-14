<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ReportContentUserEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $type;
    public $profileUrl;
    public $reportedUrl;
    public $issue;
    public $reportedOn;
    public $reporterName;
    public $emailId;
    public $phoneNumber;
    public function __construct($type, $profile_url, $reported_url, $issue, $report_on, $reporter_name, $email_id, $phone_number)
    {
        $this->type = $type;
        $this->profileUrl = $profile_url;
        $this->reportedUrl = $reported_url;
        $this->issue = $issue;
        $this->reportedOn = $report_on;
        $this->reporterName = $reporter_name;
        $this->emailId = $email_id;
        $this->phoneNumber = $phone_number;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
//    public function broadcastOn()
//    {
//        return new PrivateChannel('channel-name');
//    }
}
