<?php

namespace App\Http\Middleware;

use Closure;

class Sanitize
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
        $inputs = $request->all();
        $sanitized = \Purify::clean($inputs);
        $request->replace($sanitized);
        return $next($request);
    }
}