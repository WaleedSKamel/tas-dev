<?php

namespace App\Http\Middleware;

use Closure;

class CheckSupervisor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->guard('supervisor')->user()->blocked != 1) {
            return $next($request);
        }
        auth()->guard('supervisor')->logout();
        return redirect()->route('supervisor.login')->with('error','Your Account is blocked');
        //return $next($request);
    }
}
