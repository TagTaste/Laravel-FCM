<?php

namespace App\Http\Middleware\Api;

use Closure;

class CheckProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$request->has('profileId')){
            return response()->json(['error'=>"Missing ProfileId."]);
        }
        return $next($request);
    }
}
