<?php

namespace App\Http\Middleware\Api;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Middleware\GetUserFromToken;
use Request;
use App\Events\LogRecord;
use App\Version;
use App\Events\ContentAnalysisEvent;
use Illuminate\Support\Collection;

class Auth extends GetUserFromToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    private $versionKey = 'X-VERSION';
    private $versionKeyIos = 'X-VERSION-IOS';
    private $request,$contentAnalysisReqCollection;

    public function handle($request, Closure $next)
    {
        if(env('APP_ENV') === 'testing'){
            \Log::warning("Auto-generating token for " . env('APP_ENV') . " environment.");
            $user = \App\User::find(1);
            $token = \JWTAuth::fromUser($user);
            $this->auth->authenticate($token);

            return $next($request);
        }

        if (! $token = $this->auth->setRequest($request)->getToken()) {
            return $this->respond('tymon.jwt.absent', 'token_not_provided', 401);
        }

        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
            return response()->json(['error'=>'token_expired'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error'=>'token_invalid'], 401);
        } catch (JWTException $e) {
            return response()->json(['error'=>'token_absent'], 401);
        }

        if (! $user) {
            return $this->respond('tymon.jwt.user_not_found', 'user_not_found', 404);
        }

        $this->events->fire('tymon.jwt.valid', $user);

        $request->setUserResolver(function() use ($user){
            return $user;
        });

        $response = $next($request);

        $response->headers->set('Access-Control-Allow-Origin' , '*');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application');

        //$this->recordData($request, $response);
        return $response;
    }
    
    public function recordData($request, $response)
    {
        $versionKey = $this->versionKey;
        $versionKeyIos = $this->versionKeyIos;

        //To platform info
        if($request->hasHeader($versionKey)){$data["platform"] = "Android";$data["version"] = $request->header($versionKey);}
        else if($request->hasHeader($versionKeyIos)){$data["platform"] = "IOS";$data["version"] = $request->header($versionKeyIos);}
        else {$data["platform"] = "Web";/* $data["device"] = $request->header("User-Agent"); */}
                
        $data["method"] = $request->method();
        $data["ip"] = Request::getClientIp();          //Get ip address
        $data["url"] = $request->url();
        
        //To get User info
        $user = $request->user();
        $data["user"]["id"] = $user["id"];
        $data["user"]["name"] = $user["name"];

        //To add response field
        $data["response"]["status"] = 200;

        //Firing the event
        event(new LogRecord($data));
     }

//     public function terminate($request,$response)
//     {
//        $this->request = $request;
//        $requestDataCollection = collect($this->request->all());
//        $this->contentAnalysisReqCollection = collect ();
//
//        $this->requestValueRecursion($requestDataCollection);
//
//        if($requestDataCollection->count() > 0){
//            $tempArray = [];
//            $tempArray["type"] = "meta";
//            $tempArray["value"] = "IP- ".$this->request->ip().
//            " UserID- ".$this->request->user()->id.
//            " EndPoint- ".$this->request->fullUrl();
//            $this->contentAnalysisReqCollection->push($tempArray);
//            event(new ContentAnalysisEvent($this->contentAnalysisReqCollection));
//            $this->contentAnalysisReqCollection = null;
//        }
//     }

     private function requestValueRecursion($loopValue){
        $loopValue->each(function($val,$key){
            
            if (gettype($val) == "array" ) {
                $this->requestValueRecursion(collect($val));
            } else { 
            if ($this->request->hasFile($key)) {
                //File
                $extension = $this->request->$key->extension();
                if ($extension == "jpeg" || 
                    $extension == "jpg" || 
                    $extension == "png") 
                {
                    //Image
                    //$dump_path = $this->request->file($key."");
                    $local_storage = \Storage::disk('s3ContentAnalysis');
                    $dump_path = $local_storage->putFile('temp', $this->request->file($key.""),'public');
                    $tempArray = [];
                    $tempArray["type"] = "image";
                    $tempArray["value"] = $dump_path;
                    $this->contentAnalysisReqCollection->push($tempArray);
                } 
                else if($extension == "mp4" || $extension == "avi" || $extension == "flv" || $extension == "wmv" || $extension == "mov") {
                    //Video
                    //$dump_path = $this->request->file($key."");
                    $local_storage = \Storage::disk('s3ContentAnalysis');
                    $dump_path = $local_storage->putFile('temp', $this->request->file($key.""),'public');
                    $tempArray = [];
                    $tempArray["type"] = "video";
                    $tempArray["value"] = $dump_path;
                    $this->contentAnalysisReqCollection->push($tempArray);
                }
                
            } else {
               //Text
                    $tempArray = [];
                    $tempArray["type"] = "text";
                    $tempArray["value"] = $val;
                    $this->contentAnalysisReqCollection->push($tempArray);
            }
        
        }
        
    });

        return $this->contentAnalysisReqCollection;
     }
    
}
