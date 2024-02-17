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

        $status = $this->check_session($user_session_data);

        if($status==0){

            $user_session_data = session()->all();
        }

        if(isset($user_session_data[config('app.app_session_name')])){

            $last_active_time_Key = config('app.app_session_name').'.last_active_time';

            session()->put($last_active_time_Key, time());
        }

        if(!isset($user_session_data[config('app.app_session_name')])){

            session()->flush();
            //session()->regenerate();

            return redirect('/login');
        }
        else if($user_session_data[config('app.app_session_name')]['password_change_status']==0){

            return redirect('/change-password');
        }

        return $next($request);
    }

    protected function check_session($user_session_data){

        if(isset($user_session_data[config('app.app_session_name')])){

            $last_active_time = $user_session_data[config('app.app_session_name')]['last_active_time'];
            $user_session_time = $user_session_data[config('app.app_session_name')]['user_session_time'];

            if((time() - $last_active_time) > $user_session_time){

                session()->flush();
                //session()->regenerate();

                return 0;
            }
            else{

                return 1;
            }
        }
    }
}
