<?php

namespace App\Http\Middleware;

use Closure, Auth;

class CheckAuthentication
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
      if(Auth::check()) {

        return $next($request);

      } else {

        return redirect('/login')->with('notification-error', 'notifications.auth.required');
      }
    }
}
