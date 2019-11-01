<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class SaveSearchQuery
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
        return $next($request);
    }
    
    public function terminate($request, $response)
    {
        $user = $request->user();
        if(!$user){
            return;
        }
        $userId = $user->id;
        $key = "history:search:" . $userId;
        Redis::lPush($key, $request->q);
        Redis::lTrim($key,0,9);
    }
}
