<?php

namespace App\Http\Middleware\Api;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Middleware\GetUserFromToken;

class Auth extends GetUserFromToken
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
        if(env('APP_ENV') === 'testing'){
            \Log::warning("Auth disabled for testing environment.");
            $request->setUserResolver(function(){
                return \App\User::find(1);
            });
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

        return $next($request);
    }
    
    
}
