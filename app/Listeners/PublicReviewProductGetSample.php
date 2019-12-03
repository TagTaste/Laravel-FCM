<?php

namespace App\Listeners;

use App\Events\PublicReviewProductGetSampleEvent;
//use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;
use Monolog\Formatter\NormalizerFormatter;
use Notification;
use App\Profile;
use Mail;

class PublicReviewProductGetSample
{
    //use Notifiable;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  DocumentReject  $event
     * @return void
     */
    public function handle(PublicReviewProductGetSampleEvent $event)
    {
        $profile = Profile::where('id',$event->profileId)->first();
        $data = array(
            'profile-id' => $event->profileId,
            'name' => $profile->name,
            'email' => $event->userEmail,
            'contact' => $profile->phone,
            'product-id' => $event->productId,
            'product-name' => $event->productName
        );
        Mail::send(
            'emails.public-review-product-get-sample', 
            [
                'data' => $data
            ], 
            function ($mail) use ($data) {
                $mail->from(config('mail.from.address'), config('mail.from.name'));
                $mail->to(config('mail.tagtaste_backend_mail_id'), null)->subject("New Sample request");
            }
        );

        Mail::send(
            'emails.public-review-product-get-sample-to-user', 
            [
                'data' => $data
            ], 
            function ($mail) use ($data) {
                $mail->from(config('mail.from.address'), config('mail.from.name'));
                $mail->to($data['email'], null)->subject("Your Request for ".$data['product-name']." on TagTaste");
            }
        );
    }
}
