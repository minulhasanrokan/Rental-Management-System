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

        if(isset($user_session_data['rental'])){

            return redirect('/dashboard');

        }

        session()->flush();
        session()->regenerate();

        return $next($request);
        
    }
}
