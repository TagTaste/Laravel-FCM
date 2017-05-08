<?php

namespace App\Notifications;

use App\Job;
use App\Profile;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ShortlistApplication extends Notification
{
    use Queueable;
    private $fromEmail;
    private $fromName;
    
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($fromEmail, $fromName, Job $job)
    {
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
        $this->job = $job;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject("You have been shortlisted for " . $this->job->title . " at " . $this->job->owner()->name)
                    ->line('Congratulations!')
                    ->line($this->job->owner()->name . " is interested in you.")
                    ->line("You can reply to this mail to get in touch with " . $this->fromName . " and take this forward.")
                    ->replyTo($this->fromEmail,$this->fromName);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
