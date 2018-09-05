<?php

namespace App\Http\Middleware\Api;

use App\Version;
use Closure;

class VersionCheck
{
    private $versionKey = 'X-VERSION';
    private $versionKeyIos = 'X-VERSION-IOS';
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $isIosRequest = false;
        //if version key not specified, we've got a badass. Let 'em through.
        if(!$request->hasHeader($this->versionKey) && !$request->hasHeader($this->versionKeyIos)){
            return $next($request);
        }

        if($request->hasHeader($this->versionKey)) {
            $version = $request->header($this->versionKey);
            $api = Version::getVersion(Version::$APP_ANDROID);
        } else {
            $version = $request->header($this->versionKeyIos);
            $isIosRequest = true;
            $api = Version::getVersion(Version::$APP_IOS);
        }
        
        if(empty($version)){
            $response = response()->json(['error'=>'invalid_version',
                'message'=>'empty_version'],400);
            $response->headers->add($api->toHeaders());
            return $response;
        }
        
        //if the version is compatible;
        if(!$api->isCompatible($version) && !$isIosRequest){
            $response = $next($request);
        } else {
            $response = response()->json(['error'=>'incompatible_version',
                'message'=>'force_update'],400);
        }
        
        $response->headers->add($api->toHeaders());
        
        return $response;
    }
}
