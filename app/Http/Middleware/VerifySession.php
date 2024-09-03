<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class VerifySession
{
    public function handle($request, Closure $next)
    {
        // if (!Auth::check()) {
        //     return redirect('/login')->withErrors('Debes iniciar sesión.');
        // }
        if (!Auth::check()) {
            return redirect('/login')->withErrors('Debes iniciar sesión.');
        }

        return $next($request);
    }
}
