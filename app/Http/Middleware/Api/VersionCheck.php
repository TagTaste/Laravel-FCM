<?php

namespace App\Http\Middleware\Api;

use App\Version;
use Closure;

class VersionCheck
{
    private $versionKey = 'X-VERSION';
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $version = $request->header($this->versionKey);
    
        $api = Version::getVersion();
        //if version key not specified, we've got a badass. Let 'em through.
        //or if the version is compatible;
        if(!$version || $api->isCompatible($version)){
            $response = $next($request);
            $response->headers->add($api->toHeaders());
            return $response;
        }
        
        return response()->json(['error'=>'incompatible_version',
            'message'=>'force_update'],400);
    
    }
}
