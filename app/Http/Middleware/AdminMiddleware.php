<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
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
        if (! Auth::user()->isAdmin()) {

            if ($request->wantsJson()) {
                return response()->json(['success' => false], 403);
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}
