<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user_session_data = session()->all();

        if(isset($user_session_data[config('app.app_session_name')])){

            return redirect('/dashboard');
        }

        session()->forget(config('app.app_session_name'));
        session()->regenerate();

        return $next($request);
    }
}
