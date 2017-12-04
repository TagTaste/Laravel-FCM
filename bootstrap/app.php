<?php
 use Maxbanton\Cwh\Handler\CloudWatch;
/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    realpath(__DIR__.'/../')
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

$app->configureMonologUsing( function($monolog) {
    
    if(env('log_to_file')){
        $formatter = new \Monolog\Formatter\LineFormatter(null, null, false, true);
        $infoHandler = new \Monolog\Handler\StreamHandler('/home/vagrant/Code/webapp/storage/logs/laravel.log');
        $infoHandler->setFormatter($formatter);
        $monolog->pushHandler($infoHandler);
    }
   
    $connection = new \PhpAmqpLib\Connection\AMQPStreamConnection(
        env('rabbitmq_host','localhost'),
        env('rabbitmq_port','5672'),
        env('rabbitmq_user','test'),
        env('rabbitmq_password','test'));
    $channel = $connection->channel();
    $channel->exchange_declare('log', 'direct', false, false, false);
    
    $rmqhandler = new \App\Handler\RabbitMQHandler($channel);
    $monolog->pushHandler($rmqhandler);
    
});

return $app;
