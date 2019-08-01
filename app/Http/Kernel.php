<?php

namespace App\Http;

use App\Http\Middleware\Api\VersionCheck;
use App\Http\Middleware\SmallImage;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Barryvdh\Cors\HandleCors::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:240,1',
            'bindings',
            'sanitize',
            'versionCheck'
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'role' => \Zizaco\Entrust\Middleware\EntrustRole::class,
        'permission' => \Zizaco\Entrust\Middleware\EntrustPermission::class,
        'ability' => \Zizaco\Entrust\Middleware\EntrustAbility::class,
        //'jwt.auth' => \Tymon\JWTAuth\Middleware\GetUserFromToken::class,
        //'jwt.refresh' => \Tymon\JWTAuth\Middleware\RefreshToken::class,
        'api.auth'=> \App\Http\Middleware\Api\Auth::class,
        'api.checkProfile' => \App\Http\Middleware\Api\CheckProfile::class,
        'sanitize' => \App\Http\Middleware\Sanitize::class,
        'search.save' => \App\Http\Middleware\SaveSearchQuery::class,
        'api.CheckCompanyAdmin' => \App\Http\Middleware\Api\CheckCompanyAdmin::class,
        'versionCheck' => VersionCheck::class,
        'optimizeImages'=> \Spatie\LaravelImageOptimizer\Middlewares\OptimizeImages::class,
    ];
}
