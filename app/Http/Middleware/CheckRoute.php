<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user_session_data = session()->all();

        if(!isset($user_session_data[config('app.app_session_name')])){

            session()->flush();
            session()->regenerate();

            echo 'Session Expire';

            die;
        }
        else if($user_session_data[config('app.app_session_name')]['super_admin_status']!=1){

            $route_name = request()->route()->getName();

            //echo 'Right Not Found';
        }

        return $next($request);
    }
}
