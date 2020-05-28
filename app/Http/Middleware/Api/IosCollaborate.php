<?php

namespace App\Http\Middleware\Api;

use Closure;
use \Tagtaste\Api\SendsJsonResponse;

class IosCollaborate
{
    use SendsJsonResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->header('x-version-ios') != null 
            && version_compare("4.2.14", $request->header('x-version-ios'),">"))   {
                return  $this->sendError("You need to update version of the app from the AppStore in order to show interest in this collaboration.");
        } else 
            return $next($request);
    }
}
