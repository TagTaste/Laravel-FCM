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

    Public function saveFileToData($key,$path,&$request,&$data,$extraKey = null)
    {
        if($request->hasFile($key) && !is_null($extraKey)){

            $response = $this->saveFile($path,$request,$key);
            $data[$key] = json_encode($response,true);
            $data[$extraKey] = $response['original_photo'];
        }
    }

    Public function saveFile($path,&$request,$key)
    {
        $imageName = str_random("32") . ".jpg";
        $response['original_photo'] = \Storage::url($request->file($key)->storeAs($path."/original",$imageName,['visibility'=>'public']));
        //create a tiny image
        $path = $path."/tiny/" . str_random(20) . ".jpg";
        $thumbnail = \Image::make($request->file($key))->resize(50, null,function ($constraint) {
            $constraint->aspectRatio();
        })->blur(1)->stream('jpg',70);
        \Storage::disk('s3')->put($path, (string) $thumbnail,['visibility'=>'public']);
        $response['tiny_photo'] = \Storage::url($path);
        $response['meta'] = getimagesize($request->input($key));
        if(!$response){
            throw new \Exception("Could not save image " . $imageName . " at " . $path);
        }
        return $response;
    }
}
