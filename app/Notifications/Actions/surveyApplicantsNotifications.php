<?php


namespace App\Notifications\Actions;

use App\Notifications\Action;

use App\FCMPush;
use Carbon\Carbon;
use Illuminate\Notifications\Messages\MailMessage;

class surveyApplicantsNotifications extends Action
{
    public $view;
    public $sub;
    public $notification;
    public $surveyInfo;

    public function __construct($event)
    {
        parent::__construct($event);

        $this->surveyInfo = $event->surveyInfo;
        
        if ($event->surveyInfo["type"] == "showInterest") {
            $this->view = 'emails.survey-show-interest';
            $this->sub = htmlspecialchars_decode($event->surveyInfo["profile"]->name) . " has shown interest in your survey " . htmlspecialchars_decode($event->surveyInfo["survey_name"]);
        } else if ($event->surveyInfo["type"] == "inviteForReview") {
            $this->view = 'emails.survey-review-invite';
            $this->sub = htmlspecialchars_decode($event->surveyInfo["profile"]->name) . " has invited you to take part in the survey " . htmlspecialchars_decode($event->surveyInfo["survey_name"]);
        } else if ($event->surveyInfo["type"] == "beginSurvey") {
            $this->view = 'emails.survey-invite-accept';
            $this->sub = "You can take part in the survey " . htmlspecialchars_decode($event->surveyInfo["survey_name"]);
        }
        // $this->sub = htmlspecialchars_decode($this->data->who['name']) ." has assigned a new product (".$event->batchInfo->name.") for you to taste";
        if (!is_null($this->data->content)) {
            $this->allData['message'] = ['id' => null, 'image' => null, 'content' => $this->data->content];
        }
        $this->notification = $this->sub;
    }

    public function via($notifiable)
    {
        $via = ['database', FCMPush::class, 'broadcast'];
        if ($this->view && view()->exists($this->view)) {

            $via[] = 'mail';
        }
        return $via;
    }

    public function toMail($notifiable)
    {
        if (view()->exists($this->view)) {

            return (new MailMessage())->subject($this->sub)->view(
                $this->view,
                [
                    'data' => $this->data, 'model' => $this->allData, 'notifiable' => $notifiable,
                    'content' => $this->getContent($this->allData['content']),
                    'info' => $this->surveyInfo
                ]
            );
        }
    }

    public function toArray($notifiable)
    {
        $data = [
            'action' => $this->data->action,
            'profile' => isset(request()->user()->profile) ? request()->user()->profile : $this->data->who,
            'notification' => $this->notification,
        ];

        if (method_exists($this->model, 'getNotificationContent')) {
            $data['model'] = $this->allData;
        } else {
            \Log::warning(class_basename($this->modelName) . " doesn't specify notification content.");
            $data['model'] = [
                'name' => $this->modelName,
                'id' => $this->data->model->id,
                'content' => $this->data->content,
                'image' => $this->data->image
            ];
        }
        $data['created_at'] = Carbon::now()->toDateTimeString();

        return $data;
    }
}
