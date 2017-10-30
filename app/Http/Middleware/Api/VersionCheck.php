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
    \Log::info($version);
        $api = Version::getVersion();
        //if version key not specified, we've got a badass. Let 'em through.
        //or if the version is compatible;
        if(!$version || $api->isCompatible($version)){
            $response = $next($request);
        } else {
            $response = response()->json(['error'=>'incompatible_version',
                'message'=>'force_update'],400);
        }
        $response->headers->add($api->toHeaders());
        
        return $response;
    }
}
