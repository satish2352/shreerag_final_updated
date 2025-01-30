<?php

namespace App\Http\Middleware;

use Closure;
class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    //  public function handle($request, Closure $next, $role = null)
    //  {
    //      // Get the role_id from the session or authenticated user
    //      $userRole = $request->session()->get('role_id') ?? Auth::user()->role_id;
 
    //      if ($role && $userRole != $role) {
    //          // If the user's role doesn't match the required role, redirect them
    //          return redirect()->route('login');
    //      }
 
    //      return $next($request);
    //  }
    public function handle($request, Closure $next)
    {
        if ($request->session()->get('role_id')) {
            //$request->session()->get('role_name')
            return $next($request);
        } else {
            return redirect(route("login"));
        }
       
    }
}
