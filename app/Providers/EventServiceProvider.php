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
        'App\Events\Update' => ['App\Listeners\UpdateNotification'],

        'App\Events\Actions\Like' => [],
        'App\Events\Actions\Comment' => [],
        'App\Events\Actions\Tag' => ['App\Listeners\Notifications\Tag'],
        'App\Events\Actions\Share' => ['App\Listeners\Notifications\Share'],
        'App\Events\Chat\Invite' => ['App\Listeners\Chat\InviteNotification'],
        'App\Events\Chat\Message' => ['App\Listeners\Chat\NewMessage'],
        'App\Events\Model\Subscriber\Create' => [
                                                    'App\Listeners\Subscriber\Create'
            ],

        'App\Events\Model\Subscriber\Destroy' => ['App\Listeners\Subscriber\Destroy'],

        'App\Events\Actions\Follow' => ['App\Listeners\Notifications\Follow'],


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
