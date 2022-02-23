<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
//        'App\Events\SomeEvent' => [
//            'App\Listeners\EventListener',
//
//        ],
//        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
//            // add your listeners (aka providers) here
//           // 'SocialiteProviders\Instagram\InstagramExtendSocialite@handle',
//        ],
        'App\Events\Auth\Registered' => [
            'App\Listeners\Auth\Registered'
        ],
        'App\Events\NewFeedable' => ['App\Listeners\PushNewFeedable'],
        'App\Events\UpdateFeedable' => ['App\Listeners\UpdateFeedable'],
        'App\Events\DeleteFeedable' => ['App\Listeners\DeleteFeedable'],
        'App\Events\Searchable' => ['App\Listeners\ElasticSearch\Document'],
        'App\Events\LogRecord' => ['App\Listeners\ElasticSearch\Record'],
        'App\Events\Update' => ['App\Listeners\UpdateNotification'],
        'App\Events\DocumentRejectEvent' => ['App\Listeners\DocumentReject'],
        'App\Events\CampusConnectRequestEvent' => ['App\Listeners\CampusConnectRequest'],
        'App\Events\ReportContentUserEvent' => ['App\Listeners\ReportContentUser'],
        'App\Events\PublicReviewProductGetSampleEvent' => ['App\Listeners\PublicReviewProductGetSample'],
        'App\Events\CollaborationReportUpload' => ['App\Listeners\CollaborationReportUpload'],

        'App\Events\Actions\Like' => [],
        'App\Events\Actions\Comment' => [],
        'App\Events\Actions\Tag' => ['App\Listeners\Notifications\Tag'],
        'App\Events\Actions\Share' => ['App\Listeners\Notifications\Share'],
        'App\Events\Chat\Invite' => ['App\Listeners\Chat\InviteNotification'],
        'App\Events\Chat\Message' => ['App\Listeners\Chat\NewMessage'],
        'App\Events\Chat\V1\Message' => ['App\Listeners\Chat\V1\NewMessage'],

        'App\Events\Chat\ShareMessage' => ['App\Listeners\Chat\ShareMessages'],

        'App\Events\Model\Subscriber\Create' => [
                                                    'App\Listeners\Subscriber\Create'
            ],

        'App\Events\Model\Subscriber\Destroy' => ['App\Listeners\Subscriber\Destroy'],

        'App\Events\Actions\Follow' => ['App\Listeners\Notifications\Follow'],
        'App\Events\Actions\Apply' => ['App\Listeners\Notifications\Apply'],
        'App\Events\Actions\BeginTasting' => ['App\Listeners\Notifications\BeginTasting'],
        'App\Events\Actions\InviteForReview' => ['App\Listeners\Notifications\InviteForReview'],
        'App\Events\Actions\InvitationAcceptForReview' => ['App\Listeners\Notifications\InvitationAcceptForReview'],
        'App\Events\Actions\InvitationRejectForReview' => ['App\Listeners\Notifications\InvitationRejectForReview'],
        'App\Events\Actions\TasterEnroll' => ['App\Listeners\Notifications\TasterEnroll'],
        'App\Events\Actions\SensoryEnroll' => ['App\Listeners\Notifications\SensoryEnroll'],
        'App\Events\Actions\PaymentComplain' => ['App\Listeners\Notifications\PaymentComplain'],
        'App\Events\Actions\PaymentTransactionCreate' => ['App\Listeners\Notifications\PaymentTransactionCreate'],
        'App\Events\Actions\PaymentTransactionStatus' => ['App\Listeners\Notifications\PaymentTransactionStatus'],
        'App\Events\Actions\ReviewComment' => ['App\Listeners\Notifications\ReviewComment'],



        'App\Events\Actions\CompleteTasting' => ['App\Listeners\Notifications\CompleteTasting'],


        'App\Events\Actions\Admin' => ['App\Listeners\Notifications\Admin'],

        'App\Events\Actions\Expire' => ['App\Listeners\Notifications\Expire'],
        'App\Events\Actions\JoinFriend' => ['App\Listeners\Notifications\JoinFriend'],

        'App\Events\Actions\DeleteModel' => ['App\Listeners\Notifications\DeleteModel'],

        'App\Events\Actions\ExpireModel' => ['App\Listeners\Notifications\ExpireModel'],

        'App\Events\DeleteFilters' => ['App\Listeners\DeleteFilters'],


//        'App\Events\PhoneVerify' => ['App\Listeners\PhoneVerifyNotification'],


        'App\Events\SuggestionEngineEvent' => ['App\Listeners\SuggestionEngineListener'],


        'App\Events\ContentAnalysisEvent' => [
            'App\Listeners\ContentAnalysisListener',
        ],
        

        'App\Events\FeatureMailEvent' => ['App\Listeners\FeatureMailListener'],
        'App\Events\UpgradeApkEvent' => ['App\Listeners\UpgradeApkListener'],

        //product review collaboration upload question
        'App\Events\UploadQuestionEvent' => ['App\Listeners\UploadQuestionListener'],
        'App\Events\UpgradeIosEvent' => ['App\Listeners\UpgradeIosListener'],
        'App\Events\Chat\MessageTypeEvent'=>['App\Listeners\Chat\MessageTypeListener'],
        'App\Events\DocSubmissionEvent' =>['App\Listeners\Chat\DocSubmissionListener'],
        'App\Events\Actions\SurveyAnswered' => ['App\Listeners\Notifications\SurveyAnswered'],
        'App\Events\TransactionInit' => ['App\Listeners\TransactionInitListener'],
        'App\Events\Actions\RollbackTaster' => ['App\Listeners\Notifications\RollbackTaster'],
        'App\Events\Actions\surveyApplicantEvents' => ['App\Listeners\Notifications\surveyApplicantsListener'],
    ];

    protected $subscribe = ['App\Subscribers\Actions'];
    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
