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
            $sanitized[] = $this->clean($inputs);
            return;
        }
        
        //is_array
        foreach($inputs as $key => $value) {
            if (!is_array($value)) {
                $sanitized[$key] = $this->clean($value);
                continue;
            }
            
            $sanitized[$key] = [];
            $this->sanitize($value, $sanitized[$key]);
        }
    }
    
    private function clean($value)
    {
        return \Purify::clean($value);
    }
}