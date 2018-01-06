<?php

namespace App\Http\Middleware;

use Closure;

class SmallImage
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
        if($request->has("profile.image")){
            
        }
        return $next($request);
    }
}
