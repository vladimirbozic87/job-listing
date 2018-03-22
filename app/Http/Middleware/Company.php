<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Company
{
    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (
            Auth::guard($guard)->check() &&
            Auth::user()->company &&
            Auth::user()->company->complete == 1
        )
        {
            return $next($request);
        }

        return redirect('/');
    }
}
