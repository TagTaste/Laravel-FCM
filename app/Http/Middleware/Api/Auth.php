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
    private $request,$content_analysis_req_collection;

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
            return $this->respond('tymon.jwt.absent', 'token_not_provided', 400);
        }

        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
            return response()->json(['error'=>'token_expired'], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response()->json(['error'=>'token_invalid'], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['error'=>'token_absent'], $e->getStatusCode());
        }

        if (! $user) {
            return $this->respond('tymon.jwt.user_not_found', 'user_not_found', 404);
        }

        $this->events->fire('tymon.jwt.valid', $user);

        $request->setUserResolver(function() use ($user){
            return $user;
        });

        $response = $next($request);
        $this->recordData($request, $response);
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
        $data["response"]["status"] = $response->status();

        //Firing the event
        event(new LogRecord($data));
     }

     public function terminate($request,$response)
     {

        $this->request = $request;
        $request_data_collection = collect($this->request->all());
        $this->content_analysis_req_collection = collect ();
        $request_data_collection->each(function($val,$key){
            
            if ($this->request->hasFile($key)) {
                //File
                $extension = $this->request->$key->extension();
                if ($extension == "jpeg" || 
                    $extension == "jpg" || 
                    $extension == "png") 
                {
                    //Image
                    $local_storage = \Storage::disk('s3ContentAnalysis');
                    $dump_path = $local_storage->putFile('temp', $this->request->file($key.""),'public');
                    $tempArray = [];
                    $tempArray["type"] = "image";
                    $tempArray["value"] = $dump_path;
                    $this->content_analysis_req_collection->push($tempArray);
                } 
                else if($extension == "mp4") {
                    //Video
                    $local_storage = \Storage::disk('s3ContentAnalysis');
                    $dump_path = $local_storage->putFile('temp', $this->request->file($key.""),'public');
                    $tempArray = [];
                    $tempArray["type"] = "video";
                    $tempArray["value"] = $dump_path;
                    $this->content_analysis_req_collection->push($tempArray);
                }
            } else {
                    //Text
                    $tempArray = [];
                    $tempArray["type"] = "text";
                    $tempArray["value"] = $val;
                    $this->content_analysis_req_collection->push($tempArray);
            }
        });

        if($request_data_collection->count() > 0){
            $tempArray = [];
            $tempArray["type"] = "meta";
            $tempArray["value"] = "IP- ".$this->request->ip().
            " UserID- ".$this->request->user()->id.
            " EndPoint- ".$this->request->fullUrl();
            $this->content_analysis_req_collection->push($tempArray);
            event(new ContentAnalysisEvent($this->content_analysis_req_collection));
        }
     }
    
}
