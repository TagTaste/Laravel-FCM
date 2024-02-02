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
use App\userActivityTracking;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;
use \Tagtaste\Api\SendsJsonResponse;

use App\TempTokens;

class TempAuth
{
    use SendsJsonResponse;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    private $versionKey = 'X-VERSION';
    private $versionKeyIos = 'X-VERSION-IOS';
    private $request, $contentAnalysisReqCollection;

    public function handle($request, Closure $next)
    {
        
        $token = $request->bearerToken();
        
        $tokenDetail = TempTokens::where(\DB::raw('BINARY token'),$token)
        ->whereNull('deleted_at')
        ->where('expired_at', '>=', date("Y-m-d H:i:s"))
        ->first();

        if($tokenDetail){
            $response = $next($request);
        }else{
            return $this->sendNewError("Token validation failed.");
        }       

        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application');
        $this->logActivity($request, $tokenDetail);
        return $response;
    }

    

    public function logActivity($request, $tokenDetail)
    {
        $versionKey = $this->versionKey;
        $versionKeyIos = $this->versionKeyIos;
            
        //To platform info
        if ($request->hasHeader($versionKey)) {
            $platform = "Android";
        } else if ($request->hasHeader($versionKeyIos)) {
            $platform = "IOS";
        } else {
            $platform = "Web";
        }
            
        $token = $tokenDetail->token;
        $string = "Time : " . date("Y-m-d H:i:s") . "| Email : " . $tokenDetail->email . " | Method : " . $request->method() . " | Url : " . $request->fullUrl() . "| Platform : ".$platform."|".json_encode($request->all()).PHP_EOL.$token.PHP_EOL;

        file_put_contents(storage_path("logs") . "/questionnaire_preview_logs.txt", $string, FILE_APPEND);
    }

}
