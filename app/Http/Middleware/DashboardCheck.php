<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardCheck
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

            return redirect('/login');
        }
        else if($user_session_data[config('app.app_session_name')]['password_change_status']==0){

            return redirect('/change-password');
        }

        return $next($request);
    }
}
