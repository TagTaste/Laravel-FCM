<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Tagtaste\Api\SendsJsonResponse;

class Handler extends ExceptionHandler
{
    use SendsJsonResponse;
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
        $this->sendToSlack($exception);
    }
    
    private function sendToSlack(\Exception $e){
        \Log::info(is_null($e->getMessage()));
        if($e->getMessage() == null){
            return;
        }
        $hook = env('SLACK_HOOK');
        if(!$hook){
            \Log::warning("No hook provided for slack.");
            return;
        }
        $user = request()->user();
        
        $message = gethostname() . ":" . request()->getRequestUri() . "\n";
        if($user){
            $message .= " ($user->name:$user->id)";
        }
        $message .= ": " .$e->getMessage() . " [" . $e->getFile() . ":" . $e->getLine(). "]";
        \Log::info($message);
        $this->sendMessage($hook,$message);
    }
    
    private function sendMessage($hook,$message){
        $client =  new \GuzzleHttp\Client();
        $response = $client->request('POST', $hook,
            [
                'json' =>
                    [
                        "channel" => env('SLACK_CHANNEL'),
                        "username" => "ramukaka",
                        "icon_emoji" => ":older_man::skin-tone-3:",
                        "text" => $message]
            ]);

        \Log::info("error req response");
        \Log::info($response);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->expectsJson()) {
            return $this->apiResponse($exception);
        }
        
        return parent::render($request, $exception);
    }
    
    private function apiResponse(&$exception){
        $this->errors[] = class_basename($exception);
        
        if($exception instanceof ValidationException){
            $this->messages[] = $exception->getResponse()->original;
        } else {
            $this->messages[] = $exception->getMessage();
        }
        $this->status = 400;
        return $this->sendResponse();
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }
}
