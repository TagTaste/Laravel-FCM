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
        $sanitizedInputs = [];
        $this->sanitize($inputs,$sanitizedInputs);
        $request->replace($sanitizedInputs);
        return $next($request);
    }
    
    private function sanitize(&$inputs,&$sanitized,$parent = null)
    {
        if(!is_array($inputs)){
            $sanitized[] = \Purify::clean($inputs);
            return;
        }
        
        //is_array
        foreach($inputs as $key => $value) {
            if (!is_array($value)) {
//                if(isset($sanitized[$key]) && is_array($sanitized[$key])){
//                    $sanitized[$key][] = \Purify::clean($value);
//                } else {
                    $sanitized[$key] = \Purify::clean($value);
//                }
                continue;
            }
            
//            if(!isset($sanitized[$key])){
                $sanitized[$key] = [];
//            }
            
            $this->sanitize($value, $sanitized[$key]);
        }
    }
}